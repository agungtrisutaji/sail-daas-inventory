<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessUnitImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $importClass;

    public function __construct($filePath, $importClass)
    {
        $this->filePath = $filePath;
        $this->importClass = $importClass;
    }

    public function handle()
    {
        try {
            // Create a new instance of the import class
            $import = new $this->importClass();

            // Import the file
            Excel::import($import, Storage::path($this->filePath));

            $summary = $import->getImportSummary();

            Log::info("Import completed. Summary: " . json_encode($summary));

            // Notify admin (assuming you have a way to get admin user)
            // $admin = User::where('role', 'admin')->first();
            // if ($admin) {
            //     Notification::send($admin, new ImportCompleted($summary));
            // }

            // Delete the temporary file
            Storage::delete($this->filePath);
        } catch (\Exception $e) {
            Log::error("Error processing import job: " . $e->getMessage());
            // You might want to notify admin about the failure as well
        }
    }
}
