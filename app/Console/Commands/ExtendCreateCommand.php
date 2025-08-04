<?php

namespace App\Console\Commands;

use App\Enums\TerminationStatus;
use App\Enums\UnitStatus;
use App\Models\Extend;
use App\Models\Termination;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExtendCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:extend-create-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    /**
     *  TODO: Extend Auto Create
     * Extend created if
     * Termination->deployment->end_grace_period =  TODAY
     * deployment->is_extended = false
     * deployment->is_terminated = false
     * Termination->category = null
     * Termination->status = IN_PROGRESS
     * @return void
     */
    public function handle()
    {
        $today = Carbon::today();

        $endGracePeriodUnits = Termination::where(function ($query) use ($today) {
            $query->where(['temination_type' => null, 'status' => TerminationStatus::INPROGRESS->value])
                ->where([
                    'is_terminated' => false,
                    'is_extended' => false
                ])
                ->whereHas('deployment', function ($deploymentQuery)  use ($today) {
                    $deploymentQuery->where('status', UnitStatus::ACTIVE->value)
                        ->where('end_grace_period', '<=', $today->toDateString());
                });
        });

        $endGracePeriodUnitSerials = $endGracePeriodUnits->pluck('deployment.unit_serial');

        Log::info("Found " . $endGracePeriodUnits->count() . " unit(s) with end grace period before today. " . $today->toDateString() . " - Unit serials: " . implode(', ', $endGracePeriodUnitSerials->toArray()));

        $updatedUnits = collect([]);
        $extends = collect([]);
        $deployments = collect([]);

        foreach ($endGracePeriodUnits as $termination) {
            try {
                DB::beginTransaction();
                $unit = $termination->unit;

                $extend = Extend::create([
                    'termintaion_id' => $termination->id,
                    'deployment_id' => $termination->deployment->id,
                    'unit_serial' => $unit->serial
                ]);

                $termination->update([
                    'is_extended' => true
                ]);

                $unit->update([
                    'status' => UnitStatus::EXTENDED->value,
                    'end_contract' => null,
                    'end_grace_period' => null
                ]);

                $updatedUnits->push($unit);
                $extends->push($extend);
                $deployments->push($termination->deployment);

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error($exception->getMessage());
            }
        }

        $this->sendNotifications($updatedUnits, $extends, $deployments);
    }
}
