<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HasDeleteTempFile
{
    private function deleteTempFile($filePath, $request)
    {
        if (file_exists($filePath)) {
            try {
                unlink($filePath);
                $request->session()->forget('temp_file_path');
                Log::info("Temporary file deleted successfully: $filePath");
            } catch (\Exception $e) {
                Log::error("Failed to delete temporary file: $filePath. Error: " . $e->getMessage());
            }
        } else {
            Log::warning("Temporary file not found for deletion: $filePath");
        }
    }
}
