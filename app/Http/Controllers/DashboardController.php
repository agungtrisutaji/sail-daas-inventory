<?php

namespace App\Http\Controllers;

use App\Enums\DeliveryStatus;
use App\Enums\DeploymentStatus;
use App\Enums\StagingStatus;
use App\Enums\TerminationStatus;
use App\Enums\UnitStatus;
use App\Models\Delivery;
use App\Models\Deployment;
use App\Models\Staging;
use App\Models\Termination;
use App\Models\Unit;
use App\Traits\HasValidateEnumValue;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use HasValidateEnumValue;

    public function index()
    {

        $lastSixthMonth = collect();
        for ($i = 6; $i >= 0; $i--) {
            $lastSixthMonth->push(now()->subMonths($i)->format('Y-m-d H:i'));
        }

        $chartData = [
            'lastSixthMonth' => $lastSixthMonth->toArray(),
            'staging' => [
                'meet' => [],
                'breach' => []
            ],

            'deployment' => [
                'meet' => [],
                'breach' => []
            ],
            'termination' => [
                'meet' => [],
                'breach' => []
            ],
        ];

        // Mengisi data untuk setiap bulan
        foreach ($lastSixthMonth as $date) {
            $startDate = Carbon::parse($date)->startOfMonth();
            $endDate = Carbon::parse($date)->endOfMonth();

            // Staging
            $chartData['staging']['meet'][] = Staging::whereBetween('staging_finish', [$startDate, $endDate])
                ->where('sla', 'meet')
                ->count();
            $chartData['staging']['breach'][] = Staging::whereBetween('staging_finish', [$startDate, $endDate])
                ->where('sla', 'breach')
                ->count();

            // Deployment
            $chartData['deployment']['meet'][] = Deployment::whereBetween('created_at', [$startDate, $endDate])
                ->where('sla', 'meet')
                ->count();
            $chartData['deployment']['breach'][] = Deployment::whereBetween('created_at', [$startDate, $endDate])
                ->where('sla', 'breach')
                ->count();

            // Termination
            $chartData['termination']['meet'][] = Termination::whereBetween('created_at', [$startDate, $endDate])
                ->where('sla', 'meet')
                ->count();
            $chartData['termination']['breach'][] = Termination::whereBetween('created_at', [$startDate, $endDate])
                ->where('sla', 'breach')
                ->count();
        }

        $currentMonth = Carbon::now()->month;

        // Staging Performance
        $stagingTotal = Staging::whereNot('sla', null)->whereMonth('created_at', $currentMonth)->count();
        $stagingMet = Staging::whereNot('sla', null)->whereMonth('created_at', $currentMonth)
            ->where('sla', 'Meet')->count();
        $stagingPerformance = $stagingTotal > 0 ?
            round(($stagingMet / $stagingTotal) * 100, 2) : 0;

        // Deployment Performance
        $deploymentTotal = Deployment::whereNot('sla', null)->whereMonth('created_at', $currentMonth)->count();
        $deploymentMet = Deployment::whereNot('sla', null)->whereMonth('created_at', $currentMonth)
            ->where('sla', 'Meet')->count();
        $deploymentPerformance = $deploymentTotal > 0 ?
            round(($deploymentMet / $deploymentTotal) * 100, 2) : 0;

        // Termination Performance
        $terminationTotal = Termination::whereNot('sla', null)->whereMonth('created_at', $currentMonth)->count();
        $terminationMet = Termination::whereNot('sla', null)->whereMonth('created_at', $currentMonth)
            ->where('sla', 'Meet')->count();
        $terminationPerformance = $terminationTotal > 0 ?
            round(($terminationMet / $terminationTotal) * 100, 2) : 0;

        $chartPerformanceData = [
            'staging' => [
                'performance' => $stagingPerformance
            ],
            'deployment' => [
                'performance' => $deploymentPerformance
            ],
            'termination' => [
                'performance' => $terminationPerformance
            ]
        ];

        $stagingCount = $this->stagingCount();
        $deliveryCount = $this->deliveryCount();
        $deploymentCount = $this->deploymentCount();
        $terminationCount = $this->terminationCount();

        // dd($stagingCount, $deliveryCount, $deploymentCount);

        return view('dashboard', [
            'stagingCount' => $stagingCount,
            'deliveryCount' => $deliveryCount,
            'deploymentCount' => $deploymentCount,
            'terminationCount' => $terminationCount,
            'chartData' => $chartData,
            'chartPerformanceData' => $chartPerformanceData,
        ]);
    }

    public function stagingCount()
    {
        return Cache::remember('staging_chart', 300, function () {
            return Staging::select('id')
                ->whereNot('status', StagingStatus::COMPLETED)
                ->count();
        });
    }

    public function deliveryCount()
    {
        return Cache::remember('delivery_chart', 300, function () {
            return  Delivery::select('id')
                ->whereNot('status', DeliveryStatus::COMPLETED)
                ->count();
        });
    }

    // Silahkan implementasikan method serupa untuk operation lainnya...
    public function deploymentCount()
    {
        return Cache::remember('deployment_chart', 300, function () {
            return Deployment::select('id')
                ->whereNot('status', DeploymentStatus::COMPLETED)
                ->count();
        });
    }

    public function terminationCount()
    {
        return Cache::remember('termination_chart', 300, function () {
            return Termination::select('id')
                ->where('status', TerminationStatus::CLOSED)
                ->count();
        });
    }

    public function getUnitStatusChart(): JsonResponse
    {
        $statusCounts = [
            'Available' => Unit::where('status', UnitStatus::AVAILABLE)->count('serial'),
            'Staging' => Unit::where('status', UnitStatus::STAGING)->count('serial'),
            'Delivery' => Unit::where('status', UnitStatus::DELIVERY)->count('serial'),
            'Deployment' => Unit::where('status', UnitStatus::DEPLOYMENT)->count('serial'),
            'Termination' => Unit::where('status', UnitStatus::TERMINATION)->count('serial'),
            'Terminated' => Unit::where('status', UnitStatus::TERMINATED)->count('serial'),
            'Active' => Unit::where('status', UnitStatus::ACTIVE)->count('serial'),
            'Internal' => Unit::where('status', UnitStatus::INTERNAL)->count('serial'),
            'Sold' => Unit::where('status', UnitStatus::SOLD)->count('serial'),
            'Scrapped' => Unit::where('status', UnitStatus::SCRAPPED)->count('serial'),
            'Lost' => Unit::where('status', UnitStatus::LOST)->count('serial'),
            'Broken' => Unit::where('status', UnitStatus::BROKEN)->count('serial'),
            'Short Term' => Unit::where('status', UnitStatus::SHORTTERM)->count('serial'),
            'Extended' => Unit::where('status', UnitStatus::EXTENDED)->count('serial'),
            'Paired' => Unit::where('status', UnitStatus::PAIRED)->count('serial'),
            'Grace Period' => Unit::where('status', UnitStatus::GRACE_PERIOD)->count('serial'),
        ];

        // Remove statuses with 0 count
        $statusCounts = array_filter($statusCounts);

        return response()->json([
            'series' => array_values($statusCounts),
            'labels' => array_keys($statusCounts)
        ]);
    }

    public function getAvailableUnitsByModel()
    {
        $availableUnits = Unit::where('status', UnitStatus::AVAILABLE)
            ->selectRaw('model, COUNT(id) as count')
            ->groupBy('model')
            ->get();

        return response()->json([
            'models' => $availableUnits->pluck('model')->toArray(),
            'counts' => $availableUnits->pluck('count')->toArray()
        ]);
    }

    private function calculateStagingPerformance($month, $year)
    {
        $totalOperations = Staging::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        if ($totalOperations === 0) {
            return [
                'meetPercentage' => 0,
                'breachPercentage' => 0,
                'total' => 0
            ];
        }

        $meetCount = Staging::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where(function ($query) {
                $query->whereRaw('DATEDIFF(completed_at, created_at) <= sla');
            })
            ->count();

        $meetPercentage = round(($meetCount / $totalOperations) * 100, 2);
        $breachPercentage = round(100 - $meetPercentage, 2);

        return [
            'meetPercentage' => $meetPercentage,
            'breachPercentage' => $breachPercentage,
            'total' => $totalOperations
        ];
    }

    private function calculateDeploymentPerformance($month, $year)
    {
        $totalOperations = Deployment::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        if ($totalOperations === 0) {
            return [
                'meetPercentage' => 0,
                'breachPercentage' => 0,
                'total' => 0
            ];
        }

        $meetCount = Deployment::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where(function ($query) {
                $query->whereRaw('DATEDIFF(completed_at, created_at) <= sla');
            })
            ->count();

        $meetPercentage = round(($meetCount / $totalOperations) * 100, 2);
        $breachPercentage = round(100 - $meetPercentage, 2);

        return [
            'meetPercentage' => $meetPercentage,
            'breachPercentage' => $breachPercentage,
            'total' => $totalOperations
        ];
    }

    private function calculateTerminationPerformance($month, $year)
    {
        $totalOperations = Termination::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        if ($totalOperations === 0) {
            return [
                'meetPercentage' => 0,
                'breachPercentage' => 0,
                'total' => 0
            ];
        }

        $meetCount = Termination::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where(function ($query) {
                $query->whereRaw('DATEDIFF(completed_at, created_at) <= sla');
            })
            ->count();

        $meetPercentage = round(($meetCount / $totalOperations) * 100, 2);
        $breachPercentage = round(100 - $meetPercentage, 2);

        return [
            'meetPercentage' => $meetPercentage,
            'breachPercentage' => $breachPercentage,
            'total' => $totalOperations
        ];
    }
}
