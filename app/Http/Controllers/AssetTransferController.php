<?php

namespace App\Http\Controllers;

use App\Enums\AssetTransferStatus;
use App\Enums\AssetTransferType;
use App\Enums\CompanyCategory;
use App\Enums\DocumentAvailability;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Models\AssetTransfer;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\Unit;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;
use Yajra\DataTables\Facades\DataTables;

class AssetTransferController extends Controller
{
    public function index()
    {
        return view('asset-transfer');
    }

    public function create()
    {
        $companies = Company::select('company_name', 'id')->whereHas('addresses')->whereNotIn('company_category', [CompanyCategory::DISTRIBUTOR, CompanyCategory::OPERATIONAL])->get();
        $operationalUnits = Company::select('company_name', 'id')->whereHas('addresses')->where('company_category', CompanyCategory::OPERATIONAL)->orWhere('company_name', '=', 'PT MACRO TREND TECHNOLOGY')->get();
        $tickets = Ticket::with('unit')->where('status', TicketStatus::PENDING)->get();
        $upgradeTypes = ['SSD', 'RAM', 'HDD'];

        $units = Unit::select('id', 'serial')->get();

        return view('asset-transfer.create', [
            'companies' => $companies,
            'operationalUnits' => $operationalUnits,
            'tickets' => $tickets,
            'upgradeTypes' => $upgradeTypes,
            'units' => $units
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'ritm_number' => 'nullable|string',
                'caller' => 'required|string',
                'requestor' => 'required|string',
                'operational_unit_id' => 'nullable|exists:companies,id',
                'operational_address' => 'nullable|exists:addresses,id',
                'start_date' => 'required|date',
                'is_staging' => 'required|integer',
                'serial' => 'required|exists:units,serial',
                'from_holder_name' => 'nullable|string',
                'from_company_id' => 'nullable|exists:companies,id',
                'from_address_id' => 'nullable|exists:addresses,id',
                'to_holder_name' => 'nullable|string',
                'to_company_id' => 'required|exists:companies,id',
                'to_company_address' => 'required|exists:addresses,id',
                'company_id' => 'required|exists:companies,id',
                'company_address' => 'required|exists:addresses,id',
                'transfer_remark' => 'nullable|string',
            ],
            [
                'ritm_number.max' => 'RITM number must be less than 255 characters',
                'caller.required' => 'Caller is required',
                'requestor.required' => 'Requestor is required',
                'operational_unit_id.required' => 'Operational unit is required',
                'operational_address.required' => 'Operational address is required',
                'start_date.required' => 'Start date is required',
                'is_staging.required' => 'Staging is required',
                'serial.required' => 'Serial is required',
                'from_holder_name.max' => 'Holder Name must be less than 255 characters',
                'from_company_id.required' => 'From Company is required',
                'from_address_id.required' => 'From Loction is required',
                'to_holder_name.max' => 'to Holder name must be less than 255 characters',
                'to_company_id.required' => 'To Company is required',
                'to_company_address.required' => 'To Location is required',
                'company_id.required' => 'Requestor Company is required',
                'company_address.required' => 'Requeestor Location is required',
            ]
        );

        $unit = Unit::where('serial', $request->serial)->firstOrFail();

        $startDate = getDateTimeValue($request->start_date);

        try {
            DB::beginTransaction();

            $ticket = Ticket::create([
                'request_date' => $startDate,
                'jarvis_ticket' => $request->ritm_number,
                'unit_serial' => $unit->serial,
                'company_id' => $request->company_id,
                'company_address' => $request->company_address,
                'caller' => $request->caller,
                'requestor' => $request->requestor,
                'status' => TicketStatus::PENDING->value,
                'type' => TicketType::REQUEST->value,
            ]);

            $assetTransfer = AssetTransfer::create([
                'unit_serial' => $unit->serial,
                'ticket_id' => $ticket->id,
                'operational_unit_id' => $request->operational_unit_id,
                'operational_unit_address' => $request->operational_address,
                'start_date' => $startDate,
                'ritm_number' => $ticket->jarvis_ticket,
                'is_staging' => $request->is_staging,
                'from_holder' => $request->from_holder_name,
                'from_company_id' => $request->from_company_id,
                'from_location_id' => $request->from_address_id,
                'to_holder' => $request->to_holder_name,
                'to_company_id' => $request->to_company_id,
                'to_location_id' => $request->to_company_address,
                'transfer_remark' => $request->transfer_remark,
                'transfer_for' => $request->transfer_for ?? AssetTransferType::AFTER_DEPLOYMENT->value,
                'is_restaging' => $request->is_staging,
            ]);


            $ticket->update([
                'status' => TicketStatus::INPROGRESS->value,
            ]);

            Log::info('Asset Transfer created: ' . $assetTransfer);
            DB::commit();

            return redirect()->route('asset-transfer')->with('success', 'Asset Transfer created successfully.');
        } catch (QueryException $e) {
            DB::rollBack();
            // Log the full error message
            Log::error("Import fail. Error: " . $e->errorInfo[2]);

            $errorMessage = handleDatabaseError($e);

            return redirect()->back()->with(['error' => $errorMessage]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Asset Transfer: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to create Asset Transfer. ');
        }
    }

    public function getData(Request $request)
    {
        // Create cache key and cache time
        $cacheKey = 'get_data_' . md5(json_encode($request->all()));
        $cacheTime = 60; // cache time for 60 minute

        // Cek apakah data sudah ada di cache
        return Cache::remember($cacheKey, $cacheTime, function () use ($request) {
            $query = $this->getQuery();

            $maxRecords = 2000;
            $length = min($request->length, $maxRecords);
            $request->merge(['length' => $length > 0 ? $length : $maxRecords]);

            return DataTables::of($query)
                ->addColumn('status_badge', function ($data) {
                    return view('components.status-badge', ['status' => $data->status])->render();
                })
                ->addColumn('company_customer', function ($data) {
                    return $data->ticket->company->company_name ?? '';
                })
                ->addColumn('ticket_number', function ($data) {
                    return $data->ticket->ticket_number ?? '';
                })
                ->addColumn('jarvis_ticket', function ($data) {
                    return $data->ticket->jarvis_ticket ?? '';
                })
                ->addColumn('unit_model', function ($data) {
                    return $data->unit->model ?? '';
                })
                ->addColumn('unit_category', function ($data) {
                    return $data->unit->category ?? '';
                })
                ->addColumn('unit_service', function ($data) {
                    return $data->deployment->staging->service->label ?? '';
                })
                ->addColumn('operational_unit_name', function ($data) {
                    return $data->operationalUnit->company_name ?? '';
                })
                ->addColumn('operational_unit_location', function ($data) {
                    return $data->operationalLocation->location ?? '';
                })
                ->addColumn('from_company', function ($data) {
                    return $data->fromCompany->company_name ?? '';
                })
                ->addColumn('from_location', function ($data) {
                    return $data->fromLocation->location ?? '';
                })
                ->addColumn('to_company', function ($data) {
                    return $data->toCompany->company_name ?? '';
                })
                ->addColumn('to_location', function ($data) {
                    return $data->toLocation->location ?? '';
                })
                ->addColumn('restaging', function ($data) {
                    if ($data->is_restaging) {
                        return 'Yes';
                    } else {
                        return 'No';
                    }
                })
                ->addColumn('qc_pass', function ($data) {
                    if ($data->qc_pass) {
                        return 'Yes';
                    } elseif (!isset($data->qc_pass)) {
                        return '';
                    } else {
                        return 'No';
                    }
                })
                ->addColumn('transfer_number_action', function ($data) {
                    $id = $data->id;
                    $label = $data->transfer_number;

                    return view('components.action', [
                        'url' => route('asset-transfer.edit', $id),
                        'label' => $label,
                        'modal' => true,
                        'modalName' => 'createModal',
                    ]);
                })
                ->addIndexColumn()
                ->rawColumns(['transfer_number_action', 'status_badge', 'ticket_number', 'company_customer', 'unit_model', 'unit_category', 'unit_service', 'operational_unit_name', 'operational_unit_location', 'from_company', 'from_location', 'to_company', 'to_location'])
                ->make(true);
        });
    }

    private function getQuery()
    {
        return AssetTransfer::select(
            'asset_transfers.id',
            'asset_transfers.operational_unit_id',
            'asset_transfers.ritm_number',
            'asset_transfers.unit_serial',
            'asset_transfers.to_holder',
            'asset_transfers.to_company_id',
            'asset_transfers.to_location_id',
            'asset_transfers.from_holder',
            'asset_transfers.from_company_id',
            'asset_transfers.from_location_id',
            'asset_transfers.qc_by',
            'asset_transfers.is_restaging',
            'asset_transfers.status',
            'asset_transfers.start_date',
            'asset_transfers.finish_date',
            'asset_transfers.document_availability',
            'asset_transfers.status',
            'asset_transfers.transfer_for',
            'asset_transfers.transfer_number',
            'asset_transfers.qc_date',
            'asset_transfers.qc_pass',
            'asset_transfers.created_at',
            'asset_transfers.updated_at',
            'asset_transfers.ticket_id',
        );
    }

    public function loadAssetTransferData(AssetTransfer $assetTransfer)
    {
        $assetTransfer->load('unit', 'ticket', 'operationalUnit', 'operationalLocation', 'fromCompany', 'fromLocation', 'toCompany', 'toLocation');

        $companies = Company::whereHas('addresses')->select('company_name', 'id')->get();

        $documentOptions = DocumentAvailability::options();

        $statusOptions = AssetTransferStatus::options();

        $unit = $assetTransfer->unit;

        $operationalUnits = Company::select('company_name', 'id')->whereHas('addresses')->where('company_category', CompanyCategory::OPERATIONAL)->orWhere('company_name', '=', 'PT MACRO TREND TECHNOLOGY')->get();

        $unitDeployment = $unit->deployments()->with('company')->latest()->first();

        if ($unitDeployment) {
            $company = $unitDeployment->address->company;
            $companyLocation = $unitDeployment->address;
            $holderName = $unitDeployment->staging->holder_name;
            $service = $unitDeployment->staging->service->label;
            $serviceCategory = $unitDeployment->staging->service->service_category_label;
        } else {
            $company = null;
            $companyLocation = null;
            $holderName = null;
            $service = null;
            $serviceCategory = null;
        }

        return compact('assetTransfer', 'company', 'companyLocation', 'holderName', 'service', 'serviceCategory', 'companies', 'documentOptions', 'statusOptions', 'unit', 'operationalUnits');
    }


    public function edit(AssetTransfer $assetTransfer)
    {
        $data = $this->loadAssetTransferData($assetTransfer);
        return view('asset-transfer.edit', $data);
    }


    public function show(AssetTransfer $assetTransfer)
    {
        $data = $this->loadAssetTransferData($assetTransfer);
        return view('asset-transfer.show', $data);
    }


    public function update(Request $request, AssetTransfer $assetTransfer)
    {
        $request->validate([
            'jarvis_ticket' => 'nullable|string',
            'document_availability' => ['required', new Enum(DocumentAvailability::class)],
            'caller' => 'required|string',
            'requestor' => 'required|string',
            'company_id' => 'required|exists:companies,id',
            'company_address' => 'required|exists:addresses,id',
            'qc_by' => 'nullable|string',
            'qc_date' => 'nullable|date_format:Y-m-d H:i',
            'qc_pass' => 'nullable|integer',
            'is_restaging' => 'required|integer',
            'operational_id' => 'nullable|exists:companies,id',
            'operational_address' => 'nullable|exists:addresses,id',
            'to_holder' => 'required|string',
            'to_company_id' => 'required|exists:companies,id',
            'to_company_address' => 'required|exists:addresses,id',
            'transfer_remark' => 'nullable|string',
            'finish_date' => 'nullable|date_format:Y-m-d H:i',
            'status' => ['required', new Enum(AssetTransferStatus::class)],
        ], [
            'document_availability' => ['required', new Enum(DocumentAvailability::class)],
            'caller.required' => 'Caller name is required',
            'requestor.required' => 'Requestor name is required',
            'to_holder.required' => 'Name of To Holder is required',
            'status.required' => 'Status is required',
            'qc_pass.integer' => 'QC Pass must be a number',
            'is_restaging.required' => 'Restaging is required',
            'is_restaging.integer' => 'Restaging must be a number',
            'company_id.required' => 'Requestor Company is required',
            'company_address.required' => 'Requestor Address is required',
            'to_company_id.required' => 'To Company Address is required',
            'to_company_address.required' => 'To Company Address is required',
            'finish_date.date_format' => 'Finish Date must be in the format of YYYY-MM-DD HH:MM:SS',
            'qc_date.date_format' => 'QC Date must be in the format of YYYY-MM-DD HH:MM:SS',
        ]);

        if ($request->has('is_restaging') && $request->is_restaging == 1) {
            $request->validate([
                'operational_id' => 'required|exists:companies,id',
                'operational_address' => 'required|exists:addresses,id',
            ], [
                'operational_id.required' => 'Operational Company is required',
                'operational_address.required' => 'Operational Address is required',
            ]);
        }

        if ($request->has('qc_pass') && $request->qc_pass !== null) {
            $request->validate(
                [
                    'qc_date' => 'required|date_format:Y-m-d H:i',
                    'qc_by' => 'required|string',
                ],
                [
                    'qc_date.required' => 'QC Date is required',
                    'qc_by.required' => 'Name of QC is required',
                ]
            );
        }

        if ($request->has('qc_date') && $request->qc_date !== null) {
            $request->validate(
                [
                    'qc_pass' => 'required|integer',
                ],
                [
                    'qc_pass.required' => 'QC Pass is required',
                ]
            );
        }

        $finishDate = getDateTimeValue($request->finish_date);
        $qcDate = getDateTimeValue($request->qc_date);

        try {
            DB::beginTransaction();

            $ticket = $assetTransfer->ticket;
            if ($ticket) {
                $ticket->update([
                    'company_id' => $request->company_id,
                    'company_address' => $request->company_address,
                    'caller' => $request->caller,
                    'requestor' => $request->requestor,
                    'jarvis_ticket' => $request->jarvis_ticket
                ]);
            }

            $assetTransfer->update(
                [
                    'ritm_number' => $ticket->jarvis_ticket,
                    'to_holder' => $request->to_holder,
                    'to_company_id' => $request->to_company_id,
                    'to_location_id' => $request->to_company_address,
                    'operational_unit_id' => $request->operational_id,
                    'operational_unit_address' => $request->operational_address,
                    'qc_by' => $request->qc_by,
                    'is_restaging' => $request->is_restaging,
                    'document_availability' => $request->document_availability,
                    'finish_date' => $finishDate,
                    'transfer_for' => $request->transfer_for,
                    'qc_date' => $qcDate,
                    'qc_pass' => $request->qc_pass,
                    'status' => $request->status,
                    'transfer_remark' => $request->transfer_remark,
                ]
            );

            Log::info('Asset Transfer updated: ' . $assetTransfer);
            DB::commit();

            return redirect()->back()->with('success', 'Asset Transfer updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating asset transfer: ' . $e->getMessage());
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to update asset transfer.');
        }
    }

    public function updateState(Request $request, AssetTransfer $assetTransfer)
    {
        $request->validate([
            'finish_date' => 'nullable|date_format:Y-m-d H:i',
            'status' => ['required', new Enum(AssetTransferStatus::class)],
            'document_availability' => ['required', new Enum(DocumentAvailability::class)],
            'qc_by' => 'nullable|string',
            'qc_date' => 'nullable|date_format:Y-m-d H:i',
            'qc_pass' => 'nullable|integer',
            'transfer_remark' => 'nullable|string',
        ], [
            'status.required' => 'Status is required',
            'document_availability.required' => 'Document Availability is required',
            'finish_date.date_format' => 'Finish Date must be in the format of YYYY-MM-DD HH:MM:SS',
            'qc_date.date_format' => 'QC Date must be in the format of YYYY-MM-DD HH:MM:SS',
        ]);

        if ($request->has('qc_pass') && $request->qc_pass !== null) {
            $request->validate(
                [
                    'qc_date' => 'required|date_format:Y-m-d H:i',
                    'qc_by' => 'required|string',
                ]
            );
        }

        if ($request->has('qc_date') && $request->qc_date !== null) {
            $request->validate(
                [
                    'qc_pass' => 'required|integer',
                ]
            );
        }

        $finishDate = getDateTimeValue($request->finish_date);
        $qcDate = getDateTimeValue($request->qc_date);

        try {
            $assetTransfer->update(
                [
                    'finish_date' => $finishDate,
                    'qc_date' => $qcDate,
                    'qc_by' => $request->qc_by,
                    'qc_pass' => $request->qc_pass,
                    'status' => $request->status,
                    'document_availability' => $request->document_availability,
                    'transfer_remark' => $request->transfer_remark,
                ]
            );

            if ($assetTransfer->status === AssetTransferStatus::COMPLETED) {
                $assetTransfer->ticket->update([
                    'status' => TicketStatus::CLOSED,
                    'remarks' => 'Asset Transfer Completed',
                ]);
            }

            Log::info('Staging updated: ' . $assetTransfer);

            return redirect()->back()->with('success', 'Staging updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating staging: ' . $e->getMessage());
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', 'Serial number not found.');
            }
            return response()->json(['error' => 'An error occurred while updating staging'], 500);
        }
    }

    public function destroy(AssetTransfer $assetTransfer)
    {
        $ticket = $assetTransfer->ticket;
        try {
            DB::beginTransaction();
            if ($ticket) {
                $ticket->update([
                    'status' => TicketStatus::CANCEL,
                    'remarks' => 'Asset Transfer Deleted',
                ]);
            }

            $assetTransfer->delete();
            DB::commit();
            Log::info('Asset Transfer deleted: ' . $assetTransfer);
            return redirect()->route('asset-transfer')->with('success', 'Asset Transfer deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete asset transfer. ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete asset transfer !!');
        }
    }
}
