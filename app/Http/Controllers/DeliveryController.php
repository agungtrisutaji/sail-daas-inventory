<?php

namespace App\Http\Controllers;

use App\Enums\CompanyCategory;
use App\Enums\DeliveryCategory;
use App\Enums\DeliveryStatus;
use App\Enums\DeploymentStatus;
use App\Enums\StagingStatus;
use App\Enums\UnitCategory;
use App\Enums\UnitStatus;
use App\Imports\DeliveryImport;
use App\Models\Address;
use App\Models\Company;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\Deployment;
use App\Models\Staging;
use App\Models\Unit;
use App\Traits\HasValidateEnumValue;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DeliveryController extends Controller
{
    use HasValidateEnumValue;

    public function index()
    {
        $deliveries = Delivery::with('company')->paginate(10);
        $companies = Company::all();
        $actions = [
            'staging' => [
                'url' => route('delivery.create', DeliveryCategory::STAGING->getString()),
                'label' => 'Staging Delivery',
            ],
            'backup' => [
                'url' =>  route('delivery.create', DeliveryCategory::BACKUP->getString()),
                'label' => 'Backup Delivery',
                'disabled' => true
            ],
            'general' => [
                'url' =>  route('delivery.create', DeliveryCategory::GENERAL->getString()),
                'label' => 'General Delivery',
                'disabled' => true

            ],
        ];

        return view('delivery', compact('deliveries', 'companies', 'actions'));
    }

    public function getData(Request $request, string $type = 'staging')
    {
        if ($type == 'staging') {
            $unit = $this->getStagingQuery();
        } elseif ($type == 'part') {
            $unit = $this->getPartQuery();
        } elseif ($type == 'backup') {
            // $unit = $this->getBackupQuery();
            return;
        } else {
            $unit = $this->getItemQuery();
        }

        return DataTables::eloquent($unit)
            ->addColumn('check', '<input type="checkbox" name="unit_ids[]" value="{{ $id }}" id="unit_{{ $id }}" class="item-checkbox">')
            ->addColumn('service_name', function ($data): mixed {
                return $data->service_name ?? '-';
            })
            ->filter(function ($query) use ($request) {
                $this->dataFilter($query, $request);
            }, true)
            ->addIndexColumn()
            ->rawColumns(['check'])
            ->toJson();
    }

    public function getStagingQuery()
    {
        $query = Staging::select(['units.serial', 'units.brand', 'units.model', 'units.category as unit_category', 'units.status', 'stagings.id', 'stagings.status as staging_status', 'stagings.staging_monitor as staging_monitor', 'stagings.service_code', 'companies.company_name', 'addresses.location'])
            ->leftJoin('units', 'units.serial', 'stagings.unit_serial')
            ->leftJoin('companies', 'companies.id', 'stagings.company_id')
            ->leftJoin('addresses', 'stagings.company_address', 'addresses.id')
            ->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereIn('units.category', ['Laptop', 'Desktop'])
                        ->where('units.status', '=', 1)
                        ->where('stagings.status', '=', 1);
                })->orWhere(function ($subQuery) {
                    $subQuery->whereIn('units.category', ['Part', 'Other'])
                        ->where('units.status', '=', 0);
                })->orWhere('is_backup', '=', 1);
            });
        return $query;
    }

    public function getPartQuery()
    {
        $query = Unit::select(['units.id', 'units.serial', 'units.brand', 'units.model', 'units.category as unit_category', 'units.status', 'stagings.status as staging_status', 'stagings.service_code', 'stagings.staging_monitor as staging_monitor', 'companies.company_name', 'addresses.location'])
            ->leftJoin('stagings', 'stagings.unit_serial', 'units.serial')
            ->leftJoin('companies', 'companies.id', 'stagings.company_id')
            ->leftJoin('addresses', 'stagings.company_address', 'addresses.id')
            ->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereIn('units.category', ['Laptop', 'Desktop'])
                        ->where('units.status', '=', 1)
                        ->where('stagings.status', '=', 1);
                })->orWhere(function ($subQuery) {
                    $subQuery->whereIn('units.category', ['Part', 'Other'])
                        ->where('units.status', '=', 0);
                })->orWhere('is_backup', '=', 1);
            });
        return $query;
    }

    public function getBackupQuery()
    {
        $query = Unit::select(['units.id', 'units.serial', 'units.brand', 'units.model', 'units.category as unit_category', 'units.status', 'stagings.status as staging_status', 'stagings.service_code', 'stagings.staging_monitor as staging_monitor', 'companies.company_name', 'addresses.location'])
            ->leftJoin('stagings', 'stagings.unit_serial', 'units.serial')
            ->leftJoin('companies', 'companies.id', 'stagings.company_id')
            ->leftJoin('addresses', 'stagings.company_address', 'addresses.id')
            ->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery
                        ->where('units.status', '=', 0);
                })->orWhere(function ($subQuery) {
                    $subQuery->whereIn('units.category', ['Part', 'Other'])
                        ->where('units.status', '=', 0);
                })->orWhere('is_backup', '=', 1);
            });
        return $query;
    }

    public function getItemQuery()
    {
        //dan untuk unit yang berstatus staging sayang ingin staging dari unit tersebut berstatus COMPLETED
        $query = Unit::select(['units.id', 'units.serial', 'units.brand', 'units.model', 'units.category as unit_category', 'units.status', 'stagings.status as staging_status', 'stagings.service_code', 'stagings.staging_monitor as staging_monitor', 'companies.company_name', 'addresses.location'])
            ->leftJoin('stagings', 'stagings.unit_serial', 'units.serial')
            ->leftJoin('companies', 'companies.id', 'stagings.company_id')
            ->leftJoin('addresses', 'stagings.company_address', 'addresses.id')
            ->where(function ($query) {
                $query->whereIn('units.status', [UnitStatus::AVAILABLE->value, UnitStatus::STAGING->value])
                    ->where(function ($subQuery) {
                        $subQuery->where('units.status', UnitStatus::AVAILABLE->value)
                            ->orWhere(function ($stagingQuery) {
                                $stagingQuery->where('units.status', UnitStatus::STAGING->value)
                                    ->where('stagings.status', StagingStatus::COMPLETED->value);
                            });
                    })->orWhere(function ($subQuery) {
                        $subQuery->whereIn('units.category', ['Part', 'Other'])
                            ->where('units.status', '=', UnitStatus::AVAILABLE->value)
                        ;
                    })->orWhere('is_backup', '=', 1);
            });
        return $query;
    }

    private function dataFilter($query, Request $request)
    {
        if ($request->filled('serial')) {
            $query->where('units.serial', 'like', '%' . $request->input('serial') . '%');
        }

        if ($request->filled('brand')) {
            $query->where('units.brand', 'like', '%' . $request->input('brand') . '%');
        }

        if ($request->filled('service_code')) {
            $query->whereHas('staging', function ($q) use ($request) {
                $q->where('service_code', $request->input('service_code'));
            });
        }
        return $query;
    }

    public function create(string $type)
    {

        if ($type == 'backup') {
            return view('components.maintenance', [
                'title' => 'Delivery',
            ]);
        }

        $itemType = $type;
        $couriers = Courier::all();
        $companies = Company::whereNot('company_category', 'Distributor')->whereHas('addresses')->get();
        return view('deliveries.create', compact(['couriers', 'companies', 'itemType']));
    }

    public function store(Request $request)
    {
        /**
         * TODOS: make import excel for delivery create
         * validation check for staging->company and staging->company_address each unit
         * if staging->company and staging->company_address not matched return error
         */
        $request->validate([
            'delivery_date' => 'required|date',
            'estimated_arrival_date' => 'required|date',
            'unit_ids' => 'required|array',
            'courier_id' => 'required',
            'delivery_service_id' => 'required',
            'tracking_number' => 'required',
            'company_id' => 'nullable|exists:companies,id',
            'company_address' => 'nullable|exists:addresses,id',
            'delivery_category' => 'required',
        ]);

        $categoryEnum = $this->validateAndGetEnumValue($request->delivery_category, DeliveryCategory::class,);

        $categoryValue = $categoryEnum->value;

        $deliveryDate = getDateTimeValue($request->delivery_date);
        $estimatedArrivalDate = getDateTimeValue(strtolower($request->estimated_arrival_date));

        try {
            DB::beginTransaction();

            $delivery = Delivery::create([
                'id' => Str::orderedUuid(),
                'courier_id' => $request->courier_id,
                'delivery_service_id' => $request->delivery_service_id,
                'tracking_number' => $request->tracking_number,
                'delivery_date' => $deliveryDate,
                'estimated_arrival_date' => $estimatedArrivalDate,
                'company_id' => $request->company_id,
                'company_address' => $request->company_address,
                'status' => DeliveryStatus::DELIVERY,
                'category' => $categoryValue,
            ]);

            // Validasi company untuk staging
            $stagingCompanies = collect();

            foreach ($request->unit_ids as $unitId) {

                if ($delivery->category === DeliveryCategory::STAGING->value) {
                    $staging = Staging::find($unitId);
                    $unit = $staging->unit;

                    // Tambahkan company staging ke koleksi
                    if ($staging) {
                        $stagingCompanies->push($staging->company_id);
                    }

                    if ($request->company_id !== null && $staging->company_id !== $request->company_id) {
                        throw new \Exception("Delivery company and staging company must be the same");
                        Log::error("Delivery company and staging company must be the same");
                    }

                    Deployment::create([
                        'id' => Str::orderedUuid(),
                        'staging_id' => $staging->id ?? null,
                        'unit_serial' => $unit->serial,
                        'delivery_id' => $delivery->id,
                        'status' => DeploymentStatus::ON_DELIVERY->value,
                        'holder_name' => $staging->holder_name ?? null,
                    ]);
                } elseif ($delivery->category === DeliveryCategory::BACKUP->value) {
                    return redirect()->back()->with('error', 'Backup delivery not Available yet!');
                } else {
                    $unit = Unit::find($unitId);
                }

                $delivery->units()->attach($unit->serial);
                $unit->update(['status' => UnitStatus::DELIVERY]);
            }

            // Validasi bahwa semua staging memiliki company yang sama
            $uniqueCompanies = $stagingCompanies->unique();
            if ($uniqueCompanies->count() > 1) {
                throw new \Exception("All stagings in a delivery must belong to the same company.");
                Log::error("All stagings in a delivery must belong to the same company.");
            }

            // Set company untuk delivery jika belum di-set
            if ($delivery->company_id === null && $uniqueCompanies->count() > 0) {
                $delivery->update([
                    'company_id' => $uniqueCompanies->first(),
                    'company_address' => Staging::where('company_id', $uniqueCompanies->first())->first()->company_address
                ]);
            }

            DB::commit();

            return redirect()->route('delivery')->with('success', 'Delivery created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            Log::error('Error creating delivery: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create delivery: <br/>' . $e->getMessage());
        }
    }

    public function removeItem($deliveryId, $unitSerial)
    {
        try {
            DB::beginTransaction();

            $delivery = Delivery::findOrFail($deliveryId);
            $unit = Unit::where('serial', $unitSerial)->firstOrFail();

            // Detach unit dari delivery
            $delivery->units()->detach($unitSerial);

            // Update status unit kembali ke available/ready
            $unit->update(['status' => UnitStatus::STAGING]);

            // Hapus deployment terkait jika ada
            Deployment::where([
                'delivery_id' => $deliveryId,
                'unit_serial' => $unitSerial
            ])->delete();

            // Jika delivery tidak memiliki unit lagi, mungkin ingin menghapus delivery
            if ($delivery->units()->count() === 0) {
                $delivery->delete();
            }

            DB::commit();

            return response()->json([
                'message' => 'Item removed from delivery successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing item from delivery: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to remove item from delivery'
            ], 500);
        }
    }

    public function show(Delivery $delivery)
    {
        $delivery->load('company', 'units', 'units.stagings', 'deliveryService');

        $address = Address::findOrFail($delivery->company_address);
        $address->load('company');

        return view('deliveries.show', compact('delivery', 'address'));
    }

    public function markAsDelivered(Delivery $delivery, Request $request)
    {
        $request->validate([
            'actual_arrival_date' => 'required|date',
        ]);

        $actualArrivalDate = getDateTimeValue($request->input('actual_arrival_date'));

        $delivery->update([
            'status' => 1,
            'actual_arrival_date' => $actualArrivalDate
        ]);

        $units = $delivery->units;

        foreach ($units as $unit) {

            if ($unit->stagings()->count() > 0) {
                $staging = $unit->latestStaging;
                $unit->update(['status' => UnitStatus::DEPLOYMENT]);

                $staging->deployment->update(['status' => DeploymentStatus::PROCESSING]);
            } else {
                $unit->update(['status' => UnitStatus::SOLD]);
            }
        }

        return redirect()->route('delivery')->with('success', 'Delivery marked as delivered.');
    }
}
