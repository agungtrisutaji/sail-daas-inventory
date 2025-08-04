<?php

namespace App\Http\Controllers;

use App\Exports\ImportTemplate;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importTemplate($type)
    {

        $headings = $this->getHeadings($type);

        if (!$headings) {
            return response()->json(['message' => 'Invalid template type'], 400);
        }

        return Excel::download(new ImportTemplate($headings), "{$type}_template" . Carbon::now() . ".xlsx");
    }

    protected function getHeadings($type): ?array
    {
        $headingsMap = [
            'unit' => [
                'Serial Number',
                'Brand',
                'Model',
                'Receive Date',
            ],
            'unit_complete' => [
                'Serial Number',
                'Brand',
                'Model',
                'Receive Date',
                'Distributor',
                'Category',
            ],
            'unit-update' => [
                'Serial Number',
                'Brand',
                'Model',
                'Distributor',
                'Category',
                'Device Status',
                'Note',
            ],
            'staging' => [
                'Serial Number',
                'Monitor Serial Number',
                'Holder Name',
                'SN Termination',
            ],
        ];

        return $headingsMap[$type] ?? null;
    }
}
