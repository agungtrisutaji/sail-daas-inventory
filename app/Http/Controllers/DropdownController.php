<?php

namespace App\Http\Controllers;

use App\Enums\CompanyCategory;
use App\Enums\DeploymentStatus;
use App\Enums\StagingStatus;
use App\Enums\UnitStatus;
use App\Models\Address;
use App\Models\Company;
use App\Models\Courier;
use App\Models\DeliveryService;
use App\Models\Deployment;
use App\Models\Staging;
use App\Models\Unit;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function fetchdDeliveryService(Request $request)
    {
        $data = DeliveryService::where("courier_id", $request->courier_id)
            ->get(["name", "id"]);

        return response()->json(['data' => $data]);
    }

    /**
     * Fetches location data based on the provided ID type
     *
     * @param Request $request The incoming request
     * @param string $idType The type of ID to use for filtering.
     *                       Valid values: 'company_id', 'operational_unit_id', 'to_company_id'
     * @return JsonResponse
     */
    public function fetchLocation(Request $request, string $idType = 'company_id')
    {
        $validIdTypes = [
            'company_id' => 'company_id',
            'operational_unit_id' => 'company_id',
            'to_company_id' => 'to_company_id',
        ];

        if (!array_key_exists($idType, $validIdTypes)) {
            return response()->json(['error' => 'Invalid ID type'], 400);
        }

        $id = $request->{$idType};

        $data = Address::where("company_id", $id)
            ->get(["location", "id", "detail"]);

        return response()->json(['data' => $data]);
    }

    /**
     * Fetches location data for an operational unit
     *
     * @param Request $request The incoming request containing operational_unit_id
     * @return JsonResponse
     */
    public function fetchOperationalLocation(Request $request)
    {
        return $this->fetchLocation($request, 'operational_unit_id');
    }

    /**
     * Fetches location data for a company
     *
     * @param Request $request The incoming request containing company_id
     * @return JsonResponse
     */
    public function fetchCompanyLocation(Request $request)
    {
        return $this->fetchLocation($request);
    }

    /**
     * Fetches location data for a company
     *
     * @param Request $request The incoming request containing company_id
     * @return JsonResponse
     */
    public function fetchToCompanyLocation(Request $request)
    {
        return $this->fetchLocation($request, 'to_company_id');
    }

    public function fetchUnitDeployment(Request $request)
    {
        $unit = Deployment::where('unit_serial', '=', $request->unit_serial)
            ->first();

        if ($unit) {
            $company = $unit->address->company;
            $companyLocation = $unit->address;
            $holderName = $unit->staging->holder_name;
            $service = $unit->staging->service->label;
            $serviceCategory = $unit->staging->service->service_category_label;
        } else {
            $company = null;
            $companyLocation = null;
            $holderName = null;
            $service = null;
            $serviceCategory = null;
        }

        $unit ? $data = [
            'id' => $unit->id,
            'unit_serial' => $unit?->unit_serial ?? null,
            'company_id' => $company?->id ?? null,
            'company_name' => $company?->company_name ?? null,
            'company_group' => $company?->company_group ?? null,
            'location' => $companyLocation->location ?? null,
            'address_id' => $companyLocation->id ?? null,
            'unit_brand' => $unit?->unit->brand ?? null,
            'unit_model' => $unit?->unit->model ?? null,
            'unit_category' => $unit?->unit->category ?? null,
            'holder_name' => $holderName,
            'service' => $service,
            'service_category' => $serviceCategory
        ] : $data = null;

        return response()->json(['data' => $data]);
    }

    public function fetchUnitStaging(Request $request)
    {
        $unit = Staging::where('unit_serial', '=', $request->unit_serial)
            ->first();


        if ($unit) {
            $company = $unit->companyAddress->company;
            $companyLocation = $unit->companyAddress;
            $service = $unit->service->label;
            $serviceCategory = $unit->service->service_category_label;
        } else {
            $company = null;
            $companyLocation = null;
            $service = null;
            $serviceCategory = null;
        }

        $unit ? $data = [
            'id' => $unit->id,
            'unit_serial' => $unit?->unit_serial ?? null,
            'company_id' => $company?->id ?? null,
            'company_name' => $company?->company_name ?? null,
            'company_group' => $company?->company_group ?? null,
            'location' => $companyLocation->location ?? null,
            'address_id' => $companyLocation->id ?? null,
            'unit_brand' => $unit?->unit->brand ?? null,
            'unit_model' => $unit?->unit->model ?? null,
            'unit_category' => $unit?->unit->category ?? null,
            'service' => $service,
            'service_category' => $serviceCategory
        ] : $data = null;

        return response()->json(['data' => $data]);
    }

    public function fetchCompanies(Request $request)
    {
        $search = $request->search;
        $companies = Company::query()
            ->whereHas('addresses')
            ->when($search, function ($q) use ($search) {
                return $q->where('company_name', 'like', "%{$search}%");
            })
            ->withCount('addresses') // Jika menggunakan labelCount
            ->paginate(10);

        return response()->json($companies);
    }

    public function fetchdistributors(Request $request)
    {
        $search = $request->search;
        $companies = Company::query()
            ->whereHas('units')
            ->where('company_category', CompanyCategory::DISTRIBUTOR)
            ->when($search, function ($q) use ($search) {
                return $q->where('company_name', 'like', "%{$search}%");
            })
            ->withCount('units') // Jika menggunakan labelCount
            ->paginate(10);

        return response()->json($companies);
    }

    public function fetchUnitSerials(Request $request)
    {
        $condition = $request->condition;
        $search = $request->search;

        if ($condition == 'deployment') {
            $serials = Deployment::select('id', 'unit_serial', 'status', 'terminated')
                ->where('status', DeploymentStatus::COMPLETED->value)->whereNot('is_terminated', true)
                ->when($search, function ($q) use ($search) {
                    return $q->where('unit_serial', 'like', "%{$search}%");
                })
                ->paginate(10);
        } elseif ($condition == 'staging') {
            $serials = Staging::select('unit_serial', 'status', 'is_deployed', 'request_category')
                ->where('status', StagingStatus::COMPLETED->value)
                // ->where('request_category', '=', RequestCategory::RENEWAL)
                ->whereDoesntHave('deployment')
                ->whereNot('is_deployed',  true)
                ->when($search, function ($q) use ($search) {
                    return $q->where('serial', 'like', "%{$search}%");
                })
                ->paginate(10);
        } elseif ($condition == "available") {
            $serials = Unit::query()
                ->where('status', UnitStatus::AVAILABLE->value)
                ->when($search, function ($q) use ($search) {
                    return $q->where('serial', 'like', "%{$search}%");
                })
                ->paginate(10);
        } else {
            $serials = Unit::query()
                ->when($search, function ($q) use ($search) {
                    return $q->where('serial', 'like', "%{$search}%");
                })
                ->paginate(10);
        }

        return response()->json($serials);
    }

    public function fetchOperationalUnits(Request $request)
    {
        $search = $request->search;

        $operationalUnits = Company::where('company_category', CompanyCategory::OPERATIONAL)->orWhere('company_name', '=', 'PT MACRO TREND TECHNOLOGY')
            ->when($search, function ($q) use ($search) {
                return $q->where('serial', 'like', "%{$search}%");
            })
            ->withCount('addresses') // Jika menggunakan labelCount
            ->paginate(10);

        return response()->json($operationalUnits);
    }

    public function fetchCouriers(Request $request)
    {
        $search = $request->search;

        $couriers = Courier::query()
            ->when($search, function ($q) use ($search) {
                return $q->where('serial', 'like', "%{$search}%");
            })
            ->paginate(10);

        return response()->json($couriers);
    }
}
