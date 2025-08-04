<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ImportTemplate implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return $this->columns;
    }
}
