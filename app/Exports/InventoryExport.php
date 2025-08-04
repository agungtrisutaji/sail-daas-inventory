<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryExport implements FromGenerator, WithHeadings, ShouldAutoSize, Responsable
{
    use Exportable;

    private array $data;
    private array $columns;
    private string $fileName;

    public function __construct(array $data, array $columns)
    {
        $this->data = $data;
        $this->columns = $columns;
        $this->fileName = 'inventory_export.xlsx';
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function fileName(): string
    {
        return $this->fileName;
    }

    public function generator(): \Generator
    {
        foreach ($this->data as $row) {
            yield $this->mapRow($row);
        }
    }

    private function mapRow(array $row): array
    {
        $mapped = [];

        foreach ($this->columns as $column) {
            switch ($column) {
                case 'Serial Number':
                    $mapped[] = $row['name'] ?? '';
                    break;
                case 'Cust Company':
                    $mapped[] = $row['daascustomer_id_friendlyname'] ?? '';
                    break;
                case 'Brand':
                    $mapped[] = $row['brand_name'] ?? '';
                    break;
                case 'Model':
                    $mapped[] = $row['model_name'] ?? '';
                    break;
                case 'Status':
                    $mapped[] = $row['status'] ?? '';
                    break;
                case 'Service':
                    $mapped[] = $row['customerservice'] ?? '';
                    break;
                case 'Type':
                    $mapped[] = $row['type'] ?? '';
                    break;
                case 'Location':
                    $mapped[] = $row['location_id_friendlyname'] ?? '';
                    break;
                case 'Purchase Date':
                    $mapped[] = $row['purchase_date'] ?? '';
                    break;
                case 'Asset Number':
                    $mapped[] = $row['asset_number'] ?? '';
                    break;
                case 'Notes':
                    $mapped[] = $row['description'] ?? '';
                    break;
                default:
                    $mapped[] = '';
            }
        }

        return $mapped;
    }
}
