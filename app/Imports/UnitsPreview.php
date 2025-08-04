<?php

namespace App\Imports;

use App\Models\Unit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UnitsPreview implements ToCollection, WithHeadingRow
{

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {

        return $collection->map(function ($row) {
            $serialNumber = $row['Serial Number'];
            $unit = Unit::where('serial', $serialNumber)->first();

            return [
                'data' => $row, // Original row data
                'serial_found' => $unit !== null, // Flag indicating if serial number was found
                'serial' => $serialNumber,
            ];
        });
    }
}
