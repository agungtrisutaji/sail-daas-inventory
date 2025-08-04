<?php

namespace App\Jobs;

use App\Enums\UnitCategory;
use App\Enums\UnitStatus;
use App\Imports\UnitsPreview;
use App\Models\Unit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProcessBatchUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $data = Excel::toCollection(new UnitsPreview, $this->filePath)->first();

        DB::beginTransaction();

        try {
            $updatedCount = 0;
            $errorCount = 0;

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2;
                $serialNumber = $row['Serial Number'];
                $unit = Unit::where('serial', $serialNumber)->first();

                if (!$unit) {
                    Log::warning("Row {$rowNumber}: Unit with Serial Number '{$serialNumber}' not found.");
                    $errorCount++;
                    continue;
                }

                $updateData = $this->prepareUpdateData($row, $rowNumber);

                if (empty($updateData)) {
                    $errorCount++;
                    continue;
                }

                $unit->update($updateData);
                $updatedCount++;
            }

            DB::commit();
            Log::info("Batch update completed. {$updatedCount} unit(s) updated. {$errorCount} unit(s) skipped.");
            // Here you could send a notification to the user about the completion
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error during batch update process: " . $e->getMessage());
            // Here you could send a notification to the user about the failure
        }
    }

    private function prepareUpdateData($row, $rowNumber)
    {
        $updateData = [];

        // Process Device Status (required)
        try {
            $status = UnitStatus::from($row['Device Status']);
            $updateData['status'] = $status;
        } catch (\ValueError $e) {
            Log::warning("Row {$rowNumber}: Invalid Device Status");
            return [];
        }

        // Process optional fields
        $optionalFields = [
            'Category' => ['field' => 'category', 'enum' => UnitCategory::class],
            'Brand' => ['field' => 'brand'],
            'Model' => ['field' => 'model'],
            'Monitor Serial Number' => ['field' => 'monitor_serial'],
            'Monitor Model' => ['field' => 'monitor_model'],
            'Note' => ['field' => 'note'],
        ];

        foreach ($optionalFields as $excelColumn => $details) {
            if (isset($row[$excelColumn]) && $row[$excelColumn] !== '') {
                if (isset($details['enum'])) {
                    try {
                        $value = $details['enum']::from($row[$excelColumn]);
                        $updateData[$details['field']] = $value;
                    } catch (\ValueError $e) {
                        Log::warning("Row {$rowNumber}: Invalid {$excelColumn}");
                    }
                } else {
                    $updateData[$details['field']] = $row[$excelColumn];
                }
            }
        }

        return $updateData;
    }
}
