<?php

namespace App\Http\Controllers;

use App\Enums\UnitCategory;
use App\Imports\SaleItemsImport;
use App\Models\Sale;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $dateOptions = [
            'created_at' => 'Created',
            'updated_at' => 'Last Updated',
            'sold_at' => 'Sold At',
        ];

        $categories = UnitCategory::options();

        $units = Unit::select('id')->count('id');


        return view('sale', compact('dateOptions', 'categories', 'units'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Create sale
            $sale = Sale::create([
                'sale_date' => $request->sale_date,
                'buyer_name' => $request->buyer_name,
                'total_amount' => $request->total_amount,
                'status' => 'pending'
            ]);

            // Import items from Excel
            Excel::import(new SaleItemsImport($sale->id), $request->file('items_file'));

            DB::commit();
            return response()->json(['message' => 'Sale created successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(Sale $sale)
    {
        return response()->json([
            'sale' => $sale->load('items')
        ]);
    }

    public function getData(Request $request)
    {
        // Create cache key and cache time
        $cacheKey = 'get_data_' . md5(json_encode($request->all()));
        $cacheTime = 60; // cache time for 60 minute

        // Cek apakah data sudah ada di cache
        return Cache::remember($cacheKey, $cacheTime, function () use ($request) {
            // $query = $this->getUnitQuery();

            $maxRecords = 2000;
            $length = min($request->length, $maxRecords);
            $request->merge(['length' => $length > 0 ? $length : $maxRecords]);

            return DataTables::of(Sale::with(['units'])->select(
                'sales.*',
            ))
                ->addColumn('serial_action', $this->getSerialAction(...))

                ->filter(function ($query) use ($request) {
                    $this->dataFilter($query, $request);
                }, true)
                ->addIndexColumn()
                ->rawColumns(['serial_action'])
                ->make(true);
        });
    }

    private function getSerialAction($data)
    {
        return view('components.action', [
            'url' => route('sale.edit', $data->id),
            'label' => $data->serial,
        ])->render();
    }

    private function dataFilter($query, Request $request)
    {
        if ($request->filled('serial')) {
            $query->where('units.serial', 'like', '%' . $request->input('serial') . '%');
        }

        if ($request->filled('brand')) {
            $query->where('units.brand', 'like', '%' . $request->input('brand') . '%');
        }

        if ($request->filled('status')) {
            $query->where('units.status', $request->input('status'));
        }

        if ($request->filled('category')) {
            $query->where('units.category', $request->input('category'));
        }

        if ($request->filled('distributor_id-dropdown')) {
            $query->whereHas('distributor', function ($q) use ($request) {
                $q->where('id', $request->input('distributor_id-dropdown'));
            });
        }

        if ($request->filled('service_code')) {
            $query->whereHas('stagings', function ($q) use ($request) {
                $q->where('service_code', $request->input('service_code'));
            });
        }

        if ($request->filled('sla')) {
            $query->whereHas('stagings', function ($q) use ($request) {
                $q->where('sla', $request->input('sla'));
            });
        }

        $selectedDateColumn = $request->input('date_column');

        if (in_array($selectedDateColumn, ['created_at', 'updated_at']) && $request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $query->whereBetween("units.{$selectedDateColumn}", [$start_date, $end_date]);
        }

        if (in_array($selectedDateColumn, ['staging_start', 'staging_finish']) && $request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $query->whereBetween("stagings.{$selectedDateColumn}", [$start_date, $end_date]);
        }

        return $query;
    }
}
