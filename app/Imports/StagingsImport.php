<?php

namespace App\Imports;

use App\Enums\StagingStatus;
use App\Enums\UnitStatus;
use App\Models\Staging;
use App\Models\Unit;
use App\Traits\HasValidateEnumValue;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StagingsImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    use  HasValidateEnumValue;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private $totalRows = 0;
    private $successfulRows = 0;
    protected $serviceId;
    protected $companyId;

    public function __construct($serviceId, $companyId)
    {
        $this->serviceId = $serviceId;
        $this->companyId = $companyId;
    }

    public function model(array $row)
    {
        $this->totalRows++;

        try {
            DB::beginTransaction();

            $unit = Unit::where('serial', $row['Serial Number'])
                ->whereIn('status', [UnitStatus::AVAILABLE, UnitStatus::SHORTTERM])
                ->first();

            if (!$unit) {
                Log::warning("Unit with serial {$row['Serial Number']} not found or not available. Row: {$this->getRowNumber()}");
                throw ValidationException::withMessages([
                    'Serial Number' => "Unit with serial {$row['Serial Number']} not found or not available. Row: {$this->getRowNumber()}",
                ]);
            }

            $unit->status = UnitStatus::STAGING;
            $unit->save();
            $staging = new Staging([
                'id' => Str::orderedUuid(),
                'service_code' => $this->serviceId,
                'company_id' => $this->companyId,
                'unit_serial' => $row['Serial Number'],
                // 'holder_name' => $row['Holder Name'],
                'staging_start' => Carbon::now(),
                // 'monitor_serial' => $row['Monitor Serial Number'],
                // 'monitor_model' => $row['Monitor Model'],
                // 'cpu' => $row['CPU'],
                // 'ram' => $row['RAM'],
                // 'hdd' => $row['HDD'],
                // 'ssd' => $row['SSD'],
                // 'license' => $row['License'],
                // 'vga' => $row['VGA'],
                // 'sn_termination' => $row['SN Termination'],
                // 'note' => $row['Note'],
                'status' => StagingStatus::PROCESSING,
            ]);

            DB::commit();

            $this->successfulRows++;
            return $staging;
        } catch (ValidationException $e) {
            Log::error("Validation error importing row: " . json_encode($row) . ". Error: " . $e->getMessage());
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error importing row: " . json_encode($row) . ". Error: " . $e->getMessage());
            DB::rollBack();
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'id' => 'required|unique:stagings,id',
            'unit_serial' => [
                'required',
                'exists:units,serial',
                function ($attribute, $value, $fail) {
                    $unit = Unit::where('serial', $value)
                        ->whereIn('status', [UnitStatus::AVAILABLE, UnitStatus::SHORTTERM])
                        ->first();
                    if (!$unit) {
                        $fail("The unit with serial {$value} is not available for staging.");
                    }
                },
            ],
            'holder_name' => 'required',
            // Add other validation rules as necessary
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getRowCount(): array
    {
        return [
            'total' => $this->totalRows,
            'successful' => $this->successfulRows,
        ];
    }
}
