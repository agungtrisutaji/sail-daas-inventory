<?php

namespace App\Imports;

use App\Enums\DeliveryStatus;
use App\Enums\DeploymentStatus;
use App\Enums\UnitStatus;
use App\Models\Deployment;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Traits\HasValidateEnumValue;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DeploymentImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    use  HasValidateEnumValue;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private $totalRows = 0;
    private $successfulRows = 0;
    protected $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }


    public function model(array $row)
    {
        $this->totalRows++;

        try {
            DB::beginTransaction();

            $unit = Unit::where('serial', $row['Serial Number'])
                ->whereIn('status', [UnitStatus::DELIVERY])
                ->whereHas('stagings.deliveries', function ($query) {
                    $query->where('status', DeliveryStatus::COMPLETED);
                })
                ->first();

            if (!$unit) {
                Log::warning("Unit with serial {$row['Serial Number']} not found or not delivered. Row: {$this->getRowNumber()}");
                throw ValidationException::withMessages([
                    'Serial Number' => "Unit with serial {$row['Serial Number']} not found or not delivered. Row: {$this->getRowNumber()}",
                ]);
            }

            $unit->status = UnitStatus::DEPLOYMENT;
            $unit->save();
            $deployment = new Deployment([
                'id' => Str::orderedUuid(),
                'company_id' => $this->companyId,
                'status' => DeploymentStatus::PROCESSING,
            ]);

            DB::commit();

            $this->successfulRows++;
            return $deployment;
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
            'id' => 'required|unique:deployments,id',

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
