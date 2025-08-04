<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Models\Company;
use App\Models\OperationalUnit;
use App\Models\Ticket;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    public function index()
    {
        $companies = Company::with('addresses')->get();
        $operationalUnits = OperationalUnit::all();
        $ticketTypes = TicketType::options();
        $units = Unit::all();

        return view('components.maintenance', [
            'title' => 'Ticket',
            'units' => $units,
            'companies' => $companies,
            'operationalUnits' => $operationalUnits,
            'ticketTypes' => $ticketTypes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string',
            'unit_serial' => 'required|exists:units,serial',
            'company_id' => 'required|exists:companies,id',
            'company_address' => 'required|exists:addresses,id',
            'caller' => 'required|string',
            'requestor' => 'required|string',
            'ticket_remark' => 'nullable|string',
            'ticket_type' => 'required|integer'
        ]);


        try {
            DB::beginTransaction();

            $ticketNumber = $request->ticket_number;
            $unit = Unit::where('serial', $request->unit_serial)->firstOrFail();
            $company = Company::with('addresses')->where('id', $request->company_id)->firstOrFail();
            //det address id
            $address = $company->addresses()->where('id', $request->company_address)->firstOrFail();
            $caller = $request->caller;
            $requestor = $request->requestor;
            $ticketRemark = $request->ticket_remark;
            $ticketType = TicketType::from($request->ticket_type);

            $requestDate = getDateTimeValue($request->request_date);


            $ticket = Ticket::create([
                'request_date' => $requestDate,
                'ticket_number' => $ticketNumber,
                'unit_serial' => $unit->serial,
                'company_id' => $company->id,
                'company_address' => $address->id,
                'caller' => $caller,
                'requestor' => $requestor,
                'type' => $ticketType,
                'remarks' => $ticketRemark,
                'status' => TicketStatus::PENDING->value,

            ]);

            Log::info('Ticket created: ' . $ticket);
            DB::commit();

            return redirect()->route('ticket')->with('success', 'Ticket created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating ticket: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create ticket !!');
        }
    }

    public function getData(Request $request)
    {
        $ticket = $this->getUnitQuery();
        return DataTables::eloquent($ticket)
            ->addColumn('action', function ($data) {
                $id = $data->id;
                $actions = [
                    'edit' => [
                        'url' => route('ticket.edit', $id),
                        'label' => 'Edit',
                    ],
                    'destroy' => [
                        'url' => route('ticket.destroy', $id),
                        'label' => 'Cancel',
                        'method' => 'DELETE',
                        'confirm' => 'Are you sure you want to cancel this ticket?',
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
            ->addColumn('type_badge', function ($data) {
                return view('components.status-badge', ['status' => $data->type])->render();
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status_badge', 'type_badge'])
            ->toJson();
    }

    private function getUnitQuery()
    {
        return Ticket::with(['unit', 'address', 'company'])
            ->select(
                'tickets.*',
            )
        ;
    }
}
