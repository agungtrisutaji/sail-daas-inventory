<?php

namespace App\Imports;

use App\Enums\CompanyCategory;
use App\Models\Company;
use App\Traits\HasValidateEnumValue;
use Illuminate\Support\Str;
use App\Enums\CompanyGroup;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CompanyImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    use HasValidateEnumValue;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {

        $companyGroup = null;
        try {
            if (isset($row['Company Group']) && $row['Company Group'] !== null) {
                $companyGroup =  CompanyGroup::from(strtoupper($row['Domain']))->value;
            }

            if ($row['Company Category'] !== null) {
                try {
                    $companyCategory = $this->validateAndGetEnumValue($row['Company Category'], CompanyCategory::class);
                } catch (\ValueError $e) {
                    // Tangani kasus jika nilai tidak valid, misalnya:
                    $companyCategory = CompanyCategory::CUSTOMER->value;
                    // Atau log error, dll.
                }
            } else {
                $companyCategory = CompanyCategory::CUSTOMER->value;
            }

            $company = new Company([
                'id' => Str::orderedUuid(),
                'company_name' => $row['Company'],  // Use Excel headers
                'company_group' => $companyGroup ?? null,
                'company_code' => $row['Code'] ?? null,
                'company_category' => $companyCategory->value ?? null,
            ]);

            return $company;
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
