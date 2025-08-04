<?php

namespace App\Imports;

use App\Models\Address;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AddressImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        return $collection->map(function ($row) {
            $addressCode = $row['Code'];
            $address = Address::where('address_code', $addressCode)->first();

            return [
                'data' => $row, // Original row data
                'address_found' => $address !== null, // Flag indicating if address was found
                'address' => $addressCode,
            ];
        });
    }
}
