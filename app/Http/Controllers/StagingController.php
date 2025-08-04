<?php

namespace App\Http\Controllers;

use App\Enums\CompanyCategory;
use App\Enums\DeploymentStatus;
use App\Enums\RequestCategory;
use App\Enums\StagingStatus;
use App\Enums\TerminationStatus;
use App\Enums\TerminationType;
use App\Enums\UnitCategory;
use App\Enums\UnitStatus;
use App\Exports\StagingExport;
use Illuminate\Support\Str;
use App\Imports\ImportPreview;
use App\Imports\StagingsImport;
use App\Models\Address;
use App\Models\Company;
use App\Models\Deployment;
use App\Models\Service;
use App\Models\Staging;
use App\Models\Termination;
use App\Models\Unit;
use App\Traits\HasDeleteTempFile;
use App\Traits\HasValidateEnumValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class StagingController extends Controller
{

    use HasValidateEnumValue, HasDeleteTempFile;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        $statusOptions = StagingStatus::options();
        $companies = Company::all();
        $operationalUnits = Company::where('company_category', CompanyCategory::OPERATIONAL)->orWhere('company_name', '=', 'PT MACRO TREND TECHNOLOGY')->get();

        return view('staging', compact('services', 'statusOptions', 'companies', 'operationalUnits'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            'service_code' => 'required|exists:services,code',
            'company_id' => 'required|exists:companies,id',
        ]);

        try {
            $import = new StagingsImport($request->input('service_code'), $request->input('company_id'));
            Excel::import($import, $request->file('file'));

            $rowCounts = $import->getRowCount();
            $message = "Staging import success. Total rows: {$rowCounts['total']}, Rows success: {$rowCounts['successful']}";

            Log::info($message);

            return redirect('staging')->with('success', $message);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = collect($failures)->map(function ($failure) {
                return "Row {$failure->row()}: <strong>{$failure->errors()[0]}</strong>";
            })->implode('<br>');

            Log::error("Staging import fail. Errors: " . $errors);

            return redirect()->back()->with(['error' => "Staging import fail !! <br>" . $errors]);
        } catch (\Exception $e) {
            Log::error("Staging import fail. Error: " . $e->getMessage());

            return redirect()->back()->with(['error' => "Staging import fail !! "]);
        }
    }

    public function getData(Request $request)
    {
        $stagings = $this->getStagingQuery();

        return DataTables::of($stagings)
            ->addColumn('company_code', function ($data) {
                $companyCode = $data->company->company_code;

                return $companyCode ?? '';
            })
            ->addColumn('company_group', function ($data) {
                $companyGroup = $data->company->company_group;

                return $companyGroup ?? '';
            })
            ->addColumn('company_name', function ($data) {
                $companyName = $data->company->company_name;

                return $companyName ?? '';
            })
            ->addColumn('operational_name', function ($data) {
                $operationalUnitName = $data->operationalUnit->company_name;

                return $operationalUnitName ?? '';
            })
            ->addColumn('sn_termination', function ($data) {
                return  $data->terminated_serial ?? null;
            })
            ->addColumn('termination', function ($data) {
                return  $data->termination ?? null;
            })
            ->addColumn('company_location', function ($data) {
                $location = $data->companyAddress->location;

                return $location ?? '';
            })
            ->addColumn('operational_location', function ($data) {
                $location = $data->operationalAddress->location;

                return $location ?? '';
            })
            ->addColumn('deployment_state', function ($data) {
                if ($data->is_deployed == 1) {
                    return 'Deployed';
                }
            })
            ->addColumn('serial_action', function ($data) {
                $id = $data->id;
                $label = $data->unit_serial;

                return view('components.action', [
                    'url' => route('staging.edit', $id),
                    'label' => $label,
                    'modal' => true,
                    'modalName' => 'createModal',
                ]);
            })
            ->addColumn('action', function ($data) {
                $id = $data->id;
                $actions = [
                    'edit' => [
                        'url' => route('staging.edit', $id),
                        'label' => 'Update',
                        'modal' => true,
                        'modalName' => 'createModal',
                    ],
                    'destroy' => [
                        'url' => route('staging.destroy', $id),
                        'label' => 'Cancel',
                        'method' => 'DELETE',
                        'confirm' => 'Are you sure you want to cancel this staging?',
                    ],
                ];

                return view('components.action-list', ['actions' => $actions]);
            })
            ->addColumn('status_badge', function ($data) {
                return view('components.status-badge', ['status' => $data->status])->render();
            })
            ->addColumn('monitor', function ($data) {
                if ($data->staging_monitor) {
                    return $data->staging_monitor;
                }
            })
            ->filter(function ($query) use ($request) {
                $this->dataFilter($query, $request);
            }, true)
            ->addIndexColumn()
            ->rawColumns(['location', 'action', 'status_badge', 'serial_action', 'deployment_state'])
            ->make(true);
    }

    private function getStagingQuery()
    {
        return Staging::select(
            'stagings.id',
            'stagings.unit_serial',
            'stagings.staging_monitor',
            'stagings.termination_id',
            'terminations.id as termination_id',
            'stagings.company_id',
            'stagings.operational_unit_id',
            'stagings.operational_unit_address',
            'stagings.company_address',
            'stagings.staging_number',
            'stagings.sla',
            'stagings.holder_name',
            'stagings.status',
            'stagings.service_code',
            'stagings.request_category',
            'stagings.is_deployed',
            'stagings.staging_start',
            'stagings.staging_finish',
            'stagings.created_at',
            'stagings.updated_at',
            'units.serial',
            'units.brand',
            'units.model',
            'units.category as unit_category',
            'services.label as service_label',
            'services.code',
        )
            ->leftJoin('units', 'stagings.unit_serial', '=', 'units.serial')
            ->leftJoin('services', 'stagings.service_code', '=', 'services.code')
            ->leftJoin('terminations', 'stagings.termination_id', '=', 'terminations.id');
    }

    private function dataFilter($query, Request $request)
    {
        if ($request->filled('serial')) {
            $query->where('stagings.unit_serial', 'like', '%' . $request->input('serial') . '%');
        }

        if ($request->filled('brand')) {
            $query->whereHas('units', function ($q) use ($request) {
                $q->where('brand', $request->input('brand'));
            });
        }

        if ($request->filled('status')) {
            $query->where('stagings.status', $request->input('status'));
        }

        if ($request->filled('is_deployed')) {
            $query->where('stagings.is_deployed', $request->input('is_deployed'));
        }

        if ($request->filled('category')) {
            $query->whereHas('units', function ($q) use ($request) {
                $q->where('category', $request->input('category'));
            });
        }

        if ($request->filled('service_code')) {
            $query->whereHas('stagings.service_code', $request->input('service_code'));
        }

        if ($request->filled('sla')) {
            $query->whereHas(
                'stagings.sla',
                $request->input('sla')
            );
        }

        $selectedDateColumn = $request->input('date_column');

        if (in_array($selectedDateColumn, ['created_at', 'updated_at']) && $request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $query->whereBetween("units.{$selectedDateColumn}", [$start_date, $end_date]);
        }

        if (in_array($selectedDateColumn, ['staging_start', 'staging_finish']) && $request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $query->whereBetween("stagings.{$selectedDateColumn}", [$start_date, $end_date]);
        }

        return $query;
    }

    public function export()
    {
        $stagings = Staging::with('unit', 'service', 'company', 'company.address')->latest()->get();

        return new StagingExport($stagings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stagings.create');
    }

    /**
     * Display the specified resource.
     */
    public function loadStagingData(Staging $staging)
    {
        $statusOptions = StagingStatus::options();
        $services = Service::all();
        $companies = Company::where('company_category', CompanyCategory::CUSTOMER)->whereHas('addresses')->get();
        $categories = RequestCategory::options();

        $staging->load('unit', 'service', 'company');

        $address = null;
        if ($staging->company) {
            $address = Address::findOrFail($staging->company_address);
        }

        return compact('staging', 'services', 'statusOptions', 'companies', 'address', 'categories');
    }

    public function show(Staging $staging)
    {
        $data = $this->loadStagingData($staging);
        return view('stagings.show', $data);
    }

    public function edit(Staging $staging)
    {
        $data = $this->loadStagingData($staging);
        return view('stagings.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staging $staging)
    {
        $unit = $staging->unit;
        $unit->update(['status' => UnitStatus::AVAILABLE->value]);

        $monitorSerial = $staging->staging_monitor;
        $unitMonitor = Unit::where('serial', $monitorSerial)->first();

        if ($unitMonitor) {
            $unitMonitor->update(['status' => UnitStatus::AVAILABLE->value]);
        }

        //TODO: STAGING: fix relation between stagin and termination

        if ($staging->termination_id) {
            $termination = Termination::find($staging->termination_id);

            $termination->update([
                'status' => TerminationStatus::NEW->value,
            ]);
        }

        $staging->delete();

        return redirect()->route('staging');
    }

    public function uploadPreview(Request $request)
    {
        if (!$request->hasFile('file')) {
            return back()->with('error', 'Please select a file to upload.');
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $file = $request->file('file');
        $import = new ImportPreview();

        $data = Excel::toCollection($import, $file)->first();

        $path = $file->store('temp');
        $fullPath = storage_path('app/' . $path);

        $request->session()->put('temp_file_path', $fullPath);
        Log::info("File uploaded and path stored in session: {$fullPath}");

        $requiredColumns = ['Serial Number'];

        // Check if all required columns are present
        $missingColumns = array_diff($requiredColumns, $data->first()->keys()->toArray());

        if (!empty($missingColumns)) {
            return response()->json(['error' => 'Missing required columns: ' . implode(', ', $missingColumns)], 400);
        }

        $companies = Company::where('company_category', CompanyCategory::CUSTOMER)->whereHas('addresses')->get();
        $services = Service::all();
        $categories = RequestCategory::options();
        $statuses = StagingStatus::options();

        $serialNumbers = []; // Array untuk melacak serial number yang sudah muncul

        $processedData = $data->map(function ($row, $index) use (&$serialNumbers, $request) {
            $rowNumber = $index + 2;
            $serialNumber = $row['Serial Number'];
            $monitorSerial = null;
            $terminationSerial = null;
            $messages = [];
            $monitorPaired = [];

            // $snTermination = null;
            // TODO: STAGING: SN termination buat model data deplyment yang sudah terminated seperti DropdownController di condition 'deployment'

            // Cek apakah Serial Number kosong
            if (!$serialNumber) {
                $messages[] = "Delete empty row {$rowNumber}. Serial Number is missing.";
            } else {
                // Cek apakah Serial Number sudah ada sebelumnya (duplikat)
                if (in_array($serialNumber, $serialNumbers)) {
                    $messages[] = "Duplicate Serial Number {$serialNumber} found in row {$rowNumber}.";
                } else {
                    $serialNumbers[] = $serialNumber; // Tambahkan serial number ke array

                    // Validasi serial number di database
                    $unit = Unit::where('serial', $serialNumber)->first();
                    $unitStaging = $unit->stagings()
                        ->where('status', '!=', StagingStatus::COMPLETED)
                        ->latest()
                        ->first();

                    if (isset($row['Monitor Serial Number'])) {
                        $monitorSerial = $row['Monitor Serial Number'];
                    }

                    if (isset($row['SN Termination'])) {
                        $terminationSerial = $row['SN Termination'];
                    }

                    $unitMonitor = Unit::where('serial', $monitorSerial)->first();

                    $search = $terminationSerial ?? $request->search;

                    $termination = Termination::join('deployments', 'terminations.terminated_id', '=', 'deployments.id')
                        ->select('terminations.id', 'terminations.status', 'deployments.unit_serial')
                        ->where('terminations.status', TerminationStatus::NEW->value)
                        ->when($search, function ($q) use ($search) {
                            return $q->where('unit_serial', 'like', "%{$search}%");
                        })->first();

                    $desktop = UnitCategory::DESKTOP;
                    $monitor = UnitCategory::MONITOR;
                    $laptop = UnitCategory::LAPTOP;

                    $desktopMonitor = Staging::where('staging_monitor', $monitorSerial)->first();

                    if (!$unit) {
                        $messages[] = "Serial Number {$serialNumber} not found.";
                    } elseif ($unitStaging) {
                        $messages[] = "Serial Number {$serialNumber} is already in staging process.";
                    } else {
                        if ($unit->category == $desktop && !$monitorSerial) {
                            $messages[] = "Monitor Serial Number is missing for Desktop. Please provide Monitor Serial Number for Desktop.";
                        } else {
                            if ($unitMonitor && $unitMonitor->category != $monitor) {
                                $messages[] = "Monitor Serial Number {$monitorSerial} is not a monitor.";
                            } elseif ($unitMonitor && $unitMonitor->status == UnitStatus::PAIRED) {
                                $monitorPaired[] = "Monitor {$monitorSerial} is paired to unit {$desktopMonitor->unit_serial}. The Staging process will continue.";
                            } elseif ($unitMonitor && $unitMonitor->status != UnitStatus::AVAILABLE) {
                                $messages[] = "Monitor Serial Number {$monitorSerial} is not available.";
                            }
                        }

                        if ($unit->category == $laptop && $monitorSerial) {
                            $messages[] = "Monitor Serial Number is not allowed for Laptop.";
                        }
                    }
                }
            }

            // Gabungkan pesan menjadi satu string dan tambahkan ke baris
            $row['message'] = implode(' ', array_filter($messages));
            $row['paired_monitor'] = implode(' ', array_filter($monitorPaired));
            $row['row_number'] = $rowNumber;

            $row['termination_id'] = $termination->id ?? null;

            return $row;
        });

        $search = $request->input('search');

        $terminations = Termination::join('deployments', 'terminations.terminated_id', '=', 'deployments.id')
            ->select('terminations.id', 'terminations.status', 'deployments.unit_serial')
            ->where([
                'terminations.status' => TerminationStatus::NEW->value,
                'terminations.termination_type' => null
            ])
            ->when($search, function ($q) use ($search) {
                return $q->where('unit_serial', 'like', "%{$search}%");
            })
            ->paginate(10);

        $request->session()->put('preview_data', $processedData);

        $hasErrorMessage = $processedData->contains(function ($row) {
            return !empty($row['message']);
        });

        $pairedMonitor = $processedData->contains(function ($row) {
            return !empty($row['paired_monitor']);
        });

        $operationalUnitId = $request->input('operational_unit_id');
        $operationalUnitAddressId = $request->input('operational_address');

        session()->put('operational_unit_id', $operationalUnitId);
        session()->put('operational_unit_address', $operationalUnitAddressId);

        $operationalUnit = Company::where('id', $operationalUnitId)->first();

        $operationalUnitAddress = $operationalUnit->addresses->where('id', $operationalUnitAddressId)->first();

        $stagingNumber = Staging::generateStagingNumber();

        return view('stagings.preview', [
            'data' => $processedData,
            'companies' => $companies,
            'terminations' => $terminations,
            'services' => $services,
            'categories' => $categories,
            'statuses' => $statuses,
            'hasErrorMessage' => $hasErrorMessage,
            'pairedMonitor' => $pairedMonitor,
            'operationalUnit' => $operationalUnit,
            'operationalUnitAddress' => $operationalUnitAddress,
            'stagingNumber' => $stagingNumber
        ]);
    }

    public function processImport(Request $request)
    {
        $operationalUnits = session()->get('operational_unit_id');
        $operationalUnitAddress = session()->get('operational_unit_address');

        //TODO: STAGING: add staging relation with termination using temination_id in staging model

        // Validasi dasar
        $request->validate([
            'companies' => 'required|array',
            'services' => 'required|array',
            'categories' => 'required|array',
            'monitor_serials' => 'nullable|array',
            'holder_names' => 'nullable|array',
            'addresses' => 'required|array',
            'staging_start' => 'required',
            'batch_number' => 'required',
        ]);


        // Validasi tambahan untuk terminations
        $categories = $request->input('categories');
        foreach ($categories as $serial => $category) {
            if ($category == RequestCategory::RENEWAL->value && !isset($request->input('terminations')[$serial])) {

                $request->validate([
                    'terminations' => 'required|array|exists:terminations,id',
                ], [
                    'terminations.required' => 'SN Termination is required for Renewal Request.',
                    'terminations.exists' => 'SN Termination does not exist.',
                ]);
            }

            if ($category != RequestCategory::RENEWAL->value && isset($request->input('terminations')[$serial])) {
                throw ValidationException::withMessages([
                    'terminations' => ['SN Termination is only allowed for Renewal Request.']
                ]);
            }
        }


        //look for terminations and update termination date

        $companies = $request->input('companies');
        $services = $request->input('services');
        $categories = $request->input('categories');
        $monitors = $request->input('monitor_serials');
        $holders = $request->input('holder_names');
        $addresses = $request->input('addresses');
        $stagingStart = $request->input('staging_start');
        $terminations = $request->input('terminations');
        $stagingNumber = $request->input('batch_number');

        try {
            DB::beginTransaction();
            foreach ($companies as $serial => $companyId) {

                $unit = Unit::where('serial', $serial)->firstOrFail();
                $unit->update(['status' => UnitStatus::STAGING->value]);
                $unit->save();

                // $terminatedUnit = null;
                // if (isset($snTerminations[$serial])) {
                //     $terminatedUnit = Deployment::getTerminatedUnit($snTerminations[$serial]);
                // }

                $startDate = getDateTimeValue($stagingStart);

                $staging = Staging::create([
                    'id' => Str::orderedUuid(),
                    'batch_number' => $stagingNumber,
                    'unit_serial' => $serial,
                    'company_id' => $companyId,
                    'company_address' => $addresses[$serial] ?? null,
                    'service_code' => $services[$serial],
                    'request_category' => $categories[$serial],
                    'staging_start' => $startDate,
                    'staging_monitor' => $monitors[$serial] ?? null,
                    'holder_name' => $holders[$serial] ?? null,
                    'operational_unit_id' => $operationalUnits,
                    'operational_unit_address' => $operationalUnitAddress,
                    'termination_id' => isset($terminations[$serial]) ? $terminations[$serial] : null
                ]);

                $monitor = $staging->monitor;
                if ($monitor) {
                    $monitor->update(['status' => UnitStatus::PAIRED->value]);
                    $monitor->save();

                    $unit->update(['monitor' => $monitor->serial]);
                    $unit->save();
                }

                if (isset($terminations[$serial]) && $staging->termination) {
                    $termination = $staging->termination;

                    //TODO: STAGING: update termination type to renewal and add REQnumber for renewal

                    $termination->update([
                        'status' => TerminationStatus::INPROGRESS->value
                    ]);

                    $termination->terminated->update([
                        'is_terminated' => true,
                        'status' => TerminationStatus::CLOSED->value
                    ]);
                }
            }
            Log::info('Staging data imported successfully.');


            $filePath = session()->get('temp_file_path');

            if (File::exists($filePath)) {
                try {
                    File::delete($filePath);
                    session()->forget('temp_file_path');
                    Log::info('Temporary file deleted successfully.');
                } catch (\Exception $e) {
                    Log::error('Error deleting temporary file: ' . $e);
                }
            }

            DB::commit();
            return redirect()->route('staging')->with('success', 'Staging data imported successfully.');
        } catch (\Exception $e) {
            Log::error('Error importing staging data: ' . $e);
            DB::rollBack();
            return back()->with('error', 'Error importing staging data: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateState(Request $request, Staging $staging)
    {
        $request->validate([
            'status' => 'required',
            'staging_notes' => 'nullable',
            'staging_finish' => 'nullable|date',
        ]);

        $finishDate = getDateTimeValue($request->input('staging_finish'));

        try {
            $monitor = Staging::where('unit_serial', $staging->staging_monitor)->first();
            $staging->update(
                [
                    'status' => $request->input('status'),
                    'staging_notes' => $request->input('staging_notes'),
                    'staging_finish' => $finishDate,
                    'updated_by' => auth()->user()->id,
                    'updated_at' => now(),
                ]
            );

            if ($monitor) {
                $monitor->update([
                    'status' => $request->input('status'),
                    'staging_notes' => $request->input('staging_notes'),
                    'staging_finish' => $finishDate,
                    'updated_by' => auth()->user()->id,
                    'updated_at' => now(),
                ]);
                $monitor->save();
            }

            Log::info('Staging updated: ' . $staging);

            return redirect()->back()->with('success', 'Staging updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating staging: ' . $e->getMessage());
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', 'Serial number not found.');
            }
            return response()->json(['error' => 'An error occurred while updating staging'], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staging $staging)
    {
        $request->validate([
            'staging_monitor' => 'nullable',
            'holder_name' => 'required',
            'company_id' => 'required',
            'company_address' => 'required',
            'service_code' => 'required',
            'request_category' => 'required',
            'status' => 'required',
            'staging_notes' => 'nullable',
            'staging_finish' => 'nullable|date',
        ]);

        $finishDate = getDateTimeValue($request->input('staging_finish'));


        try {
            DB::beginTransaction();

            $staging->update(
                [
                    'status' => $request->input('status'),
                    'holder_name' => $request->input('holder_name'),
                    'company_id' => $request->input('company_id'),
                    'company_address' => $request->input('company_address'),
                    'service_code' => $request->input('service_code'),
                    'request_category' => $request->input('request_category'),
                    'staging_monitor' => $request->input('staging_monitor'),
                    'staging_notes' => $request->input('staging_notes'),
                    'staging_finish' => $finishDate,
                    'updated_by' => auth()->user()->id,
                    'updated_at' => now(),
                ]
            );


            Log::info('Staging updated: ' . $staging);
            DB::commit();

            return redirect()->back()->with('success', 'Staging updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating staging: ' . $e->getMessage());
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to update staging.');
        }
    }
}
