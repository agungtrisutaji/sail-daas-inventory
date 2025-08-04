<?php

namespace App\Http\Controllers;

use App\Enums\DeploymentStatus;
use App\Models\Deployment;
use Illuminate\Support\Str;
use App\Models\Unit;
use App\Enums\UnitStatus;
use App\Imports\DeploymentImport;
use App\Imports\ImportPreview;
use App\Models\Address;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DeploymentController extends Controller
{
    public function index()
    {
        $deployments = $this->getDeploymentQuery();
        $statusOptions = DeploymentStatus::options();
        $companies = Company::all();

        return view('deployment', compact('statusOptions', 'companies'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            'company_id' => 'required|exists:companies,id',
        ]);

        try {
            $import = new DeploymentImport($request->input('company_id'));
            Excel::import($import, $request->file('file'));

            $rowCounts = $import->getRowCount();
            $message = "Deployment import success. Total rows: {$rowCounts['total']}, Rows success: {$rowCounts['successful']}";

            Log::info($message);

            return redirect('deployment')->with('success', $message);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = collect($failures)->map(function ($failure) {
                return "Row {$failure->row()}: <strong>{$failure->errors()[0]}</strong>";
            })->implode('<br>');

            Log::error("Deployment import fail. Errors: " . $errors);

            return redirect()->back()->with(['error' => "Deployment import fail !! <br>" . $errors]);
        } catch (\Exception $e) {
            Log::error("Deployment import fail. Error: " . $e->getMessage());

            return redirect()->back()->with(['error' => "Deployment import fail !! "]);
        }
    }

    public function getData(Request $request)
    {
        $deployments = $this->getDeploymentQuery();

        return DataTables::eloquent($deployments)
            ->addColumn('serial_action', function ($data) {
                $id = $data->id;
                $label = $data->unit_serial;

                return view('components.action', [
                    'url' => route('deployment.edit', $id),
                    'label' => $label,
                    'modal' => true,
                    'modalName' => 'createModal',
                ]);
            })
            ->addColumn('terminated_serial', function ($data) {
                return  $data->terminated_serial ?? null;
            })
            ->addColumn('request_category_label', function ($data) {
                return  $data->staging->request_category_label ?? null;
            })
            ->addColumn('company_name', function ($data) {
                return  $data->company_name ?? null;
            })
            ->addColumn('company_location', function ($data) {
                return  $data->company_location ?? null;
            })
            ->addColumn('action', function ($data) {
                $id = $data->id;
                $actions = [
                    'edit' => [
                        'url' => route('deployment.edit', $id),
                        'label' => 'Update',
                        'modal' => true,
                        'modalName' => 'createModal',
                    ],
                    'destroy' => [
                        'url' => route('deployment.destroy', $id),
                        'label' => 'Cancel',
                        'method' => 'DELETE',
                        'confirm' => 'Are you sure you want to cancel this deployment?',
                    ],
                ];

                return view('components.action-list', ['actions' => $actions]);
            })
            ->addColumn('status_badge', function ($data) {
                return view('components.status-badge', ['status' => $data->status])->render();
            })
            ->filter(function ($query) use ($request) {
                $this->dataFilter($query, $request);
            }, true)
            ->addIndexColumn()
            ->rawColumns(['action', 'status_badge'])
            ->toJson();
    }

    private function getDeploymentQuery()
    {
        return Deployment::select(
            'deployments.*',
            'stagings.staging_monitor',
            'stagings.request_category',
            'stagings.holder_name',
            'units.serial',
            'units.brand',
            'units.model',
            'units.category',
            'units.status as unit_status',
            'services.label as service_label',
            'services.code',
        )
            ->leftJoin('stagings', 'deployments.staging_id', '=', 'stagings.id')
            ->leftJoin('units', 'deployments.unit_serial', '=', 'units.serial')
            ->leftJoin('services', 'stagings.service_code', '=', 'services.code');

        // return
        //     Unit::where('status', [UnitStatus::DEPLOYMENT])->with(['stagings', 'stagings.service', 'stagings.company']);
    }

    public function dataFilter($query, Request $request)
    {

        if ($request->filled('serial')) {
            $query->whereHas('units', function ($q) use ($request) {
                $q->where('serial', 'like', '%' . $request->input('serial') . '%');
            });
        }
    }

    public function uploadPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $file = $request->file('file');
        $import = new ImportPreview();
        $data = Excel::toCollection($import, $file)->first();

        $companies = Company::all();
        $statuses = DeploymentStatus::options();

        return view('deployments.preview', compact('data', 'companies', 'statuses'));
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'companies' => 'required|array',
        ]);

        $companies = $request->input('companies');

        DB::beginTransaction();
        try {
            foreach ($companies as $serial => $companyId) {

                if (empty($companyId)) {
                    throw new \Exception("Company not selected for serial number: {$serial}");
                }

                $unit = Unit::where('serial', $serial)->firstOrFail();

                if (!$unit->stagings) {
                    throw new \Exception("No staging found for unit with serial number: {$serial}");
                }
                $unit->update(['status' => UnitStatus::DEPLOYMENT->value]);
                $unit->save();

                Deployment::create([
                    'id' => Str::orderedUuid(),
                    'staging_id' => $unit->stagings->first()->id,
                    'company_id' => $companyId,
                ]);
            }
            Log::info('Deployment data imported successfully.');

            DB::commit();
            return redirect()->route('deployment')->with('success', 'Deployment data imported successfully.');
        } catch (\Exception $e) {
            Log::error('Error importing deployment data: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->route('deployment')->with('error', 'Error importing deployment data: ');
        }
    }

    public function loadDeploymentData(Deployment $deployment)
    {
        $statusOptions = DeploymentStatus::options();
        $companies = Company::all();
        $deployment->load(['staging', 'company']);

        $address = null;
        if ($deployment->company) {
            $address = Address::findOrFail($deployment->company_address);
        }

        return compact('deployment', 'statusOptions', 'companies', 'address');
    }

    public function edit(Deployment $deployment)
    {
        $data = $this->loadDeploymentData($deployment);

        return view('deployments.edit', $data);
    }

    public function show(Deployment $deployment)
    {
        $data = $this->loadDeploymentData($deployment);

        return view('deployments.show', $data);
    }

    public function updateState(Request $request, Deployment $deployment)
    {
        $request->validate([
            'status' => 'required',
            'bast_number' => 'string|nullable',
            'deployment_note' => 'string|nullable',
            'bast_date' => 'date|nullable|date_format:Y-m-d H:i',
            'approved_by' => 'string|nullable',
            'bast_sign_date' => 'date|nullable',
            'ritm_number' => 'string|nullable',
            'holder_name' => 'string|nullable',
        ]);

        try {
            DB::transaction(function () use ($request, $deployment) {
                $deployment->update([
                    'status' => $request->input('status'),
                    'bast_number' => $request->input('bast_number'),
                    'deployment_note' => $request->input('deployment_note'),
                    'bast_date' => $request->input('bast_date'),
                    'approved_by' => $request->input('approved_by'),
                    'bast_sign_date' => $request->input('bast_sign_date'),
                    'ritm_number' => $request->input('ritm_number'),
                ]);

                if ($request->input('status') == DeploymentStatus::COMPLETED->value) {

                    $request->validate(
                        [
                            'holder_name' => 'required|string',
                            'bast_number' => 'required|string',
                            'bast_date' => 'required|date_format:Y-m-d H:i',
                        ],
                        [
                            'holder_name.required' => 'Holder name is required for completed deployment.',
                            'bast_number.required' => 'Bast number is required for completed deployment.',
                            'bast_date.required' => 'Bast date is required for completed deployment.',
                            'bast_date.date_format' => 'Bast date must be in Y-m-d H:i format.',
                        ]
                    );
                    $holderName = $request->input('holder_name');

                    if (isset($holderName) && !empty($holderName)) {
                        $deployment->staging->update(['holder_name' => $holderName]);
                    }
                }

                if ($deployment->status == DeploymentStatus::COMPLETED) {

                    $deployment->staging->update(['is_deployed' => 1,]);
                    $deployment->staging->unit->update(['status' => UnitStatus::ACTIVE,]);
                }
            });

            Log::info('Deployment updated successfully :' . $deployment);

            return redirect()->route('deployment')->with('success', 'Deployment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating deployment: ' . $deployment);
            Log::error('Error updating deployment: ' . $e);
            Log::error('Error updating deployment: ' . $e->getMessage());
            return redirect()->route('deployment')->with('error', 'Error updating deployment: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Deployment $deployment)
    {
        $request->validate([
            'status' => 'required',
            'bast_number' => 'string|nullable',
            'deployment_note' => 'string|nullable',
            'bast_date' => 'date|nullable',
            'approved_by' => 'string|nullable',
            'bast_sign_date' => 'date|nullable',
            'ritm_number' => 'string|nullable',
            'holder_name' => 'string|nullable',
            'company' => 'required|exists:companies,id',
            'address' => 'required|exists:addresses,id',
        ]);

        try {
            DB::beginTransaction();
            $deployment->update([
                'status' => $request->input('status'),
                'holder_name' => $request->input('holder_name'),
                'bast_number' => $request->input('bast_number'),
                'deployment_note' => $request->input('deployment_note'),
                'bast_date' => $request->input('bast_date'),
                'approved_by' => $request->input('approved_by'),
                'bast_sign_date' => $request->input('bast_sign_date'),
                'ritm_number' => $request->input('ritm_number'),
                'company_id' => $request->input('company'),
                'company_address' => $request->input('address')
            ]);

            if ($deployment->status == DeploymentStatus::COMPLETED) {
                $deployment->staging->update(['is_deployed' => 1]);
            }

            $deployment->save();

            Log::info('Deployment updated successfully :' . $deployment);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating deployment: ' . $e->getMessage());
        }
    }

    public function destroy(Deployment $deployment)
    {
        try {
            $staging = $deployment->staging;
            $staging->update(['is_deployed' => 0]);

            $unit = $deployment->unit;
            $unit->update(['status' => UnitStatus::STAGING->value]);

            $delivery = $deployment->delivery;

            // Detach unit dari delivery
            $delivery->units()->detach($unit->serial);

            // Jika delivery tidak memiliki unit lagi, mungkin ingin menghapus delivery
            if ($delivery->units()->count() === 0) {
                $delivery->delete();
            }

            $deployment->delete();

            Log::info('Deployment deleted successfully :' . $deployment);
            return redirect()->route('deployment')->with('success', 'Deployment deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting deployment: ' . $e->getMessage());
            return redirect()->route('deployment')->with('error', 'Error deleting deployment: ' . $e->getMessage());
        }
    }
}
