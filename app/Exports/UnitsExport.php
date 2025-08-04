<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Excel;
use Yajra\DataTables\Exports\DataTablesCollectionExport;

class UnitsExport extends DataTablesCollectionExport implements FromArray, WithStrictNullComparison, WithHeadings, WithMapping, ShouldAutoSize, Responsable
{
    use Exportable;
    protected $data;

    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */
    private $fileName = 'units.xlsx';

    /**
     * Optional Writer Type
     */
    private $writerType = Excel::XLSX;

    /**
     * Optional headers
     */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->data->toArray();
    }
    public function headings(): array
    {
        return [
            'Serial Number',
            'Category',
            'Brand',
            'Model',
            "Device Status",
            'Staging Start',
            'Staging Finish',
            'Monitor Serial Number',
            "Monitor Model",
            'Note',
        ];
    }

    public function map($row): array
    {
        return [
            $row['serial'],
            $row['category'],
            $row['brand'],
            $row['model'],
            $row['status_label'],
            $row['stagings']['staging_start'] ?? '',
            $row['stagings']['staging_finish'] ?? '',
            $row['monitor_serial'],
            $row['monitor_model'],
            $row['note'],
        ];
    }
}
