<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class StagingExport implements FromArray, WithHeadings, WithMapping, ShouldAutoSize, Responsable
{
    use Exportable;
    protected $data;

    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */
    private $fileName = 'stagings.xlsx';

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
            'Service',
            'Holder Name',
            'Staging Start',
            'Staging Finish',
            'SLA',
            'Company Name',
            'Company Address',
            'Monitor Serial Number',
            'Monitor Model',
            'Unit Status',
            'Request Name',
            'SN Termination',
            'Note',
        ];
    }

    public function map($row): array
    {
        return [
            $row['unit_serial'],
            $row["service"]["label"],
            $row["holder_name"],
            $row["staging_start"],
            $row["staging_finish"],
            $row["sla"],
            $row["company"]["company_name"],
            $row["company"]["address"]['location'],
            $row["staging_monitor"],
            $row["staging_monitor_model"],
            $row['unit']['status_label'],
            $row["request_category_label"],
            $row["sn_termination"],
            $row["note"],
        ];
    }
}
