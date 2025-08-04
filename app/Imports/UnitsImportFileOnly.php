<?php

namespace App\Imports;


use App\Enums\AssetGroup;
use App\Enums\UnitCategory;
use App\Enums\UnitStatus;
use App\Models\Company;
use App\Models\Unit;
use App\Traits\HasValidateEnumValue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UnitsImportFileOnly implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithValidation
{
    use  HasValidateEnumValue;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private $totalRows = 0;
    private $successfulRows = 0;
    protected $receiveNumber;
    protected $status;

    public function __construct(string $receiveNumber)
    {
        $this->receiveNumber = $receiveNumber;
    }


    public function model(array $row)
    {
        $this->totalRows++;

        try {
            $status = UnitStatus::AVAILABLE;
            $distributor = Company::where('company_code', $row['Distributor'])->first();
            if (!$distributor) {
                throw ValidationException::withMessages([
                    'distributor' => " {$this->getRowNumber()} : Distributor not found.",
                ]);

                throw new \Exception("Distributor not found.");

                Log::error("Distributor not found importing row: " . json_encode($row));
            } else {
                $distributorId = $distributor->id;
            }
            $category = $this->validateAndGetEnumValue($row['Category'], UnitCategory::class, 'category');
            $assetGroup = AssetGroup::DAAS;
            $receiveNumber = $this->receiveNumber;
            $receiveDate = getDateTimeValue(Date::excelToDateTimeObject($row['Receive Date']));

            if ($assetGroup == AssetGroup::BACKUP->value) {
                $backup = true;
            } else {
                $backup = false;
            }


            $unit = new Unit([
                'id' => Str::orderedUuid(),
                'receive_date' => $receiveDate,
                'receive_number' => $receiveNumber,
                'serial' => $row['Serial Number'],
                'brand' => $row['Brand'],
                'model' => $row['Model'],
                'category' => $category,
                'distributor_id' => $distributorId,
                'asset_group' => $assetGroup->value,
                'note' => $row['Note'] ?? null,
                'monitor_serial' => $row['Monitor Serial Number'] ?? null,
                'monitor_model' => $row['Monitor Model'] ?? null,
                'status' => $status,
                'is_backup' => $backup
            ]);

            $this->successfulRows++;
            return $unit;
        } catch (ValidationException $e) {
            Log::error("Validation error importing row: " . json_encode($row) . ". Error: " . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error importing row: " . json_encode($row) . ". Error: " . $e->getMessage());

            session()->flash('error', 'Error importing: ' . $e->getMessage());
        }
    }

    public function rules(): array
    {
        return [
            'Serial Number' => 'required|unique:units,serial',
            'Brand' => 'required',
            'Model' => 'required',
            'Device Status' => ['nullable'],
        ];
    }

    public function uniqueBy()
    {
        return 'serial';
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function getRowCount(): array
    {
        return [
            'total' => $this->totalRows,
            'successful' => $this->successfulRows,
        ];
    }
}
