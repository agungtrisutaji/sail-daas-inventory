<?php

namespace App\Http\Controllers;

use App\Enums\TerminationStatus;
use App\Enums\TerminationType;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Enums\UnitStatus;
use App\Models\Deployment;
use App\Models\Staging;
use App\Models\Termination;
use App\Models\Ticket;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

//TODO: TERMINATION: decision of extend or terminate unit based on 'end_contract_date' on deployment. showing unit list when 'end_contract_date' is a month before current date

//TODO: TERMINATION: Termination create with available unit for renewal
class TerminationController extends Controller
{
    public function index()
    {
        return view("termination", [
            "title" => "Termination",
        ]);
    }

    public function create()
    {
        $terminationType = TerminationType::options();
        return view("terminations.create", [
            "title" => "Create Termination",
            "terminationTypes" => $terminationType,
        ]);
    }

    public function store(Request $request)
    {
        //TODO: TERMINATION: update staging to save sn_termination after termination is created
        //TODO: TERMINATION: default type terminated only
        $request->validate([
            'termination_type' => ['required', new Enum(TerminationType::class)],
            'ticket_number' => 'nullable|string|max:15',
            // 'request_date' => 'required|date_format:Y-m-d H:i',
            'company_id' => 'required|exists:companies,id',
            'company_address' => 'required|exists:addresses,id',
            'termination_remark' => 'nullable',
            // 'sn_renewal' => 'nullable|exists:stagings,unit_serial',
            'sn_termination' => 'required|exists:deployments,unit_serial',
            'holder_name' => 'nullable|max:100',
            'termination_company_id' => 'required|exists:companies,id',
            'termination_company_address' => 'required|exists:addresses,id',
            'requestor_name' => 'required',
            'terminated_id' => 'required|unique:terminations,terminated_id|exists:deployments,id'
        ], [
            'termination_type' => ['required', new Enum(TerminationType::class)],
            'ticket_number.max' => 'Ticket number must be less than 15 characters',
            // 'request_date.required' => 'Request date is required',
            // 'request_date.date_format' => 'Request date must be in the format of YYYY-MM-DD HH:MM:SS',
            'company_id.required' => 'Company is required',
            'company_address.required' => 'Company address is required',
            'termination_remark.max' => 'Transfer remark must be less than 255 characters',
            // 'sn_renewal.exists' => 'The Serial Number Renewal field does not exist.',
            'sn_termination.exists' => 'The Serial Number Termination field does not exist.',
            'holder_name.max' => 'Holder name must be less than 100 characters',
            'termination_company_id.required' => 'Termination company is required',
            'termination_company_address.required' => 'Termination company address is required',
            'requestor_name.required' => 'Requestor is required',
            'terminated_id.required' => 'Termination Unit is required',
            'terminated_id.exists' => 'Termination Unit does not exist',
            'terminated_id.unique' => 'Termintaion For this unit already exists',
        ]);

        $deployment = Deployment::findOrFail($request->terminated_id);

        $unit = Unit::where('serial', '=', $request->sn_termination)->firstOrFail();

        $requestDate = getDateTimeValue($request->request_date, true);

        try {
            DB::beginTransaction();

            $ticket = Ticket::create([
                'jarvis_ticket' => $request->ticket_number,
                'unit_serial' => $deployment->unit_serial,
                'company_id' => $request->company_id,
                'company_address' => $request->company_address,
                'caller' => $request->holder_name,
                'requestor' => $request->requestor_name,
                'status' => TicketStatus::PENDING->value,
                'type' => TicketType::REQUEST->value,
                'request_date' => $requestDate,
            ]);

            //TODO: TERMINATION: default category for termination is null. update in staging process for renewal, update on modal for terminate only

            $termination = Termination::create([
                'terminated_id' => $deployment->id,
                'termination_type' => $request->termination_type,
                'ticket_id' => $ticket->id,
                'request_date' => $requestDate,
                'company_id' => $request->company_id,
                'company_address' => $request->company_address,
                'termination_remark' => $request->termination_remark ?? null,
                "requestor_name" => $request->requestor_name,
                "holder_name" => $request->holder_name,
                "termination_company_id" => $request->termination_company_id,
                "termination_company_address" => $request->termination_company_address,
                'status' => TerminationStatus::NEW->value,
                'end_contract_date' => $deployment->end_contract,
                'renewal_date' => $request->request_date ?? null,
            ]);

            $unit->update([
                'status' => UnitStatus::ALLOCATED->value,
                'alocated_for' => UnitStatus::DEPLOYMENT->value,
                'alocated_for_id' => $termination->id,
            ]);

            $termination->terminated->update([
                'is_terminated' => true,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $termination,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
