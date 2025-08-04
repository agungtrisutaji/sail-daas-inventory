<?php

namespace App\Http\Controllers;

use App\Enums\CompanyCategory;
use App\Enums\RequestUpgradeStatus;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Models\Address;
use App\Models\Company;
use App\Models\RequestUpgrade;
use App\Models\Ticket;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class RequestUpgradeController extends Controller
{
    public function index()
    {
        return view('request-upgrade');
    }

    public function create()
    {
        $companies = Company::with('addresses')->whereHas('addresses')->get();
        $operationalUnits = Company::where('company_category', CompanyCategory::OPERATIONAL)->orWhere('company_name', '=', 'PT MACRO TREND TECHNOLOGY')->get();
        $tickets = Ticket::with('unit')->where('status', TicketStatus::PENDING)->get();
        $upgradeTypes = ['RAM PC 8GB', 'RAM PC 16GB', 'RAM SODIMM 8 GB', 'RAM SODIMM 16 GB', 'SSD 256 GB', 'SSD 512 GB', 'SSD 1TB', 'SSD 2TB'];

        $units = Unit::all();

        return view('request-upgrades.create', [
            'companies' => $companies,
            'operationalUnits' => $operationalUnits,
            'tickets' => $tickets,
            'upgradeTypes' => $upgradeTypes,
            'units' => $units
        ]);
    }


    public function store(Request $request)
    {
        if (!$request->has('ticket_number')) {
            return response()->json([
                'success' => false,
                'message' => 'Please select ticket number'
            ]);
        }

        if (!$request->has('unit_serial')) {
            return response()->json([
                'success' => false,
                'message' => 'Please select unit serial'
            ]);
        }

        if (!$request->has('company_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Please select company'
            ]);
        }

        if (!$request->has('company_address')) {
            return response()->json([
                'success' => false,
                'message' => 'Please select company address'
            ]);
        }

        if (!$request->has('caller')) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter caller'
            ]);
        }

        if (!$request->has('requestor')) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter requestor'
            ]);
        }

        if (!$request->has('operational_unit_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Please select operational unit'
            ]);
        }

        if (!$request->has('operational_unit_address')) {
            return response()->json([
                'success' => false,
                'message' => 'Please select operational unit address'
            ]);
        }

        if (!$request->has('upgrade_type')) {
            return response()->json([
                'success' => false,
                'message' => 'Please select upgrade type'
            ]);
        }

        $request->validate([
            'ticket_number' => 'required|string',
            'unit_serial' => 'required|exists:units,serial',
            'company_id' => 'required|exists:companies,id',
            'company_address' => 'required|exists:addresses,id',
            'caller' => 'required|string',
            'requestor' => 'required|string',
            'operational_unit_id' => 'required|exists:companies,id',
            'operational_unit_address' => 'required|exists:addresses,id',
            'bast_date' => 'required|date',
            'engineer' => 'required|string',
            'upgrade_remark' => 'nullable|string',
            'upgrade_type' => 'required|integer'
        ]);

        try {
            DB::beginTransaction();

            $ticket = Ticket::create([
                // 'request_date' => getDateTimeValue(Carbon::now()),
                'ticket_number' => $request->ticket_number,
                'unit_serial' => $request->unit_serial,
                'company_id' => $request->company_id,
                'company_address' => $request->company_address,
                'caller' => $request->caller,
                'requestor' => $request->requestor,
                'status' => TicketStatus::NEW->value,
                'type' => TicketType::REQUEST->value,
            ]);

            $unit = Unit::where('serial', $ticket->unit_serial)->firstOrFail();
            $operationalUnit = Company::find($request->operational_unit_id);
            $operationalUnitAddress = Address::find($request->operational_unit_address);
            $upgradeType = $request->upgrade_type;
            $bastDate = $request->bast_date;
            $offeringPrice = parseCurrency($request->offering_price);
            $expensePart = parseCurrency($request->part_expense);
            $expneseEngineer = parseCurrency($request->engineer_expense);
            $expenseDelivery = parseCurrency($request->delivery_expense);
            $upgradeRemark = $request->upgrade_remark;
            $engineer = $request->engineer;
            $expenseTotal = $expneseEngineer + $expenseDelivery + $expensePart;

            $requestUpgrade = RequestUpgrade::create([
                'ticket' => $ticket->ticket_number,
                'operational_unit_id' => $operationalUnit->id,
                'operational_unit_address' => $operationalUnitAddress->id,
                'bast_date' => $bastDate,
                'engineer' => $engineer,
                'status' => RequestUpgradeStatus::OFFERING->value,
                'offering_price' => $offeringPrice,
                'expense_part' => $expensePart,
                'expense_engineer' => $expneseEngineer,
                'expense_delivery' => $expenseDelivery,
                'expense_total' => $expenseTotal,
            ]);

            $requestUpgrade->upgradeDetail()->create([
                'upgrade_type' => $upgradeType,
                'upgrade_remark' => $upgradeRemark,
            ]);

            $ticket->update([
                'status' => TicketStatus::INPROGRESS->value,
            ]);

            Log::info('Request upgrade created: ' . $requestUpgrade);
            DB::commit();

            return redirect()->route('upgrade')->with('success', 'Request Upgrade created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating request upgrade: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create request upgrade');
        }
    }

    public function getData(Request $request)
    {
        $upgrade = $this->getUnitQuery();
        return DataTables::eloquent($upgrade)
            ->addColumn('operational_unit_name', function ($data) {
                return $data->operationalUnit->company_name;
            })
            ->addColumn('action', function ($data) {
                $id = $data->id;
                $actions = [
                    'edit' => [
                        'url' => route('upgrade.edit', $id),
                        'label' => 'Edit',
                    ],
                    'destroy' => [
                        'url' => route('upgrade.destroy', $id),
                        'label' => 'Cancel',
                        'method' => 'DELETE',
                        'confirm' => 'Are you sure you want to cancel this upgrade?',
                    ],
                ];
                return view('components.action-list', ['actions' => $actions]);
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
            ->addIndexColumn()
            ->rawColumns(['action', 'status_badge'])
            ->toJson();
    }

    private function getUnitQuery()
    {
        return RequestUpgrade::with(['ticket', 'ticket.unit', 'operationalUnit', 'upgradeDetail'])
            ->select(
                'request_upgrades.*',
                'upgrade_details.upgrade_type as upgrade_type',
                'upgrade_details.upgrade_remark as upgrade_remark',
                'tickets.ticket_number',
                'tickets.status as ticket_status',
                'tickets.remarks as ticket_remarks',
                'tickets.unit_serial as ticket_unit_serial',
                'tickets.caller as ticket_caller',
                'tickets.requestor as ticket_requestor',
                'tickets.type as ticket_type',
                'units.serial as unit_serial_number',
                'units.brand as unit_brand',
                'units.model as unit_model',

            )
            ->leftJoin('upgrade_details', 'request_upgrades.id', '=', 'upgrade_details.request_upgrade_id')
            ->leftJoin('tickets', 'request_upgrades.ticket', '=', 'tickets.ticket_number')
            ->leftJoin('units', 'tickets.unit_serial', '=', 'units.serial');
    }
}
