<?php

namespace App\Exports;

use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InventoryViewExport implements FromView
{
    public function view(): View
    {
        $datas = Unit::with('stagings')->get();
        $columns = ['serial', 'brand', 'model', 'category', 'status',];

        return view('components.export-table', compact('datas', 'columns'));
    }
}
