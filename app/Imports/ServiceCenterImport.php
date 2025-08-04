<?php

namespace App\Imports;

use App\Enums\CompanyCategory;
use App\Models\Company;
use Illuminate\Support\Str;
use App\Enums\CompanyGroup;
use App\Enums\ServiceCenterCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ServiceCenterImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        try {
            $companyGroup =  CompanyGroup::from(strtoupper($row['Domain']))->value;

            $companyCategory = CompanyCategory::OPERATIONAL->value;

            $serviceCategoryRow = $row['Service Category'];

            if ($serviceCategoryRow == 'DAAS') {
                $serviceCategory = ServiceCenterCategory::DAAS->value;
            }

            if ($serviceCategoryRow == 'EXTEND') {
                $serviceCategory = ServiceCenterCategory::EXTEND->value;
            }

            return new Company([
                'id' => Str::orderedUuid(),
                'company_name' => $row['Company'],  // Use Excel headers
                'company_group' => $companyGroup,
                'company_code' => $row['Code'] ?? null,
                'company_category' => $companyCategory,
                'is_service_center' => true,
                'service_category' => $serviceCategory,
            ]);
        } catch (ValidationException $e) {
            Log::error("Validation error importing row: " . json_encode($row) . ". Error: " . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error importing row: " . json_encode($row) . ". Error: " . $e->getMessage());

            session()->flash('error', 'Error importing: ' . $e->getMessage());
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
