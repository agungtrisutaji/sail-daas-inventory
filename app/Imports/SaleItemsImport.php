<?php

namespace App\Imports;

use App\Models\SaleItem;
use Maatwebsite\Excel\Concerns\ToModel;

class SaleItemsImport implements ToModel
{
    private $saleId;

    public function __construct($saleId)
    {
        $this->saleId = $saleId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        return new SaleItem([
            'sale_id' => $this->saleId,
            'serial_number' => $row['serial_number'],
            'asset_id' => $row['asset_id'],
            'price' => $row['price'] ?? 0
        ]);
    }
}
