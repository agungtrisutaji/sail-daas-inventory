<?php

namespace App\Http\Controllers;

use App\Enums\AssetGroup;
use App\Enums\UnitStatus;
use App\Exports\UnitsExport;
use App\Helpers\DatabaseErrorHelper;
use App\Imports\UnitsImport;
use App\Imports\UnitsPreview;
use App\Models\Unit;
use App\Enums\UnitCategory;
use App\Imports\UnitsImportFileOnly;
use App\Models\Company;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class UnitController extends Controller
{


    public function index(Request $request)
    {
        return redirect()->route('inventory');
    }

    public function preview(Request $request)
    {
        $file = $request->file('file');
        $data = Excel::toCollection(new UnitsPreview(), $file)->toArray();
        foreach ($data as $key => $rows) {
            foreach ($rows as $rowKey => $row) {
                $serialNumber = $row['Serial Number'];
                $unit = Unit::where('serial', $serialNumber)->first();

                if (!$unit) {
                    $data[$key][$rowKey]['message'] = 'Serial Number ' . $serialNumber . ' not found.';
                }
            }
        }

        return view('units.preview', compact('data'));
    }

    public function export()
    {
        $units = Unit::with('stagings')->latest()->get();

        return new UnitsExport($units);
    }

    public function import(Request $request)
    {
        // dd($request->all());
        if (!$request->hasFile('file')) {
            return back()->with('error', 'Please select a file to upload.');
        }

        if (!$request->has('category')) {
            return back()->with('error', 'Please select a category.');
        }

        if (!$request->has('distributor_id')) {
            return back()->with('error', 'Please select a distributor.');
        }

        if (!$request->has('asset_group')) {
            return back()->with('error', 'Please select an asset group.');
        }

        // if (!$request->has('receive_date')) {
        //     return back()->with('error', 'Please select a receive date.');
        // }

        if (!UnitCategory::tryFrom($request->input('category'))) {
            return back()->with('error', 'The selected category is invalid.');
        }

        if (!Company::find($request->input('distributor_id'))) {
            return back()->with('error', 'The selected distributor is invalid.');
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            'category' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!UnitCategory::tryFrom($value)) {
                    $fail("The selected category is invalid.");
                }
            }],
            'distributor_id' => 'required|exists:companies,id',
        ]);

        try {
            DB::beginTransaction();

            $category = UnitCategory::from($request->input('category'));
            $distributor = Company::find($request->input('distributor_id'));
            $assetGroup = AssetGroup::from($request->input('asset_group'));
            // $receiveDate = getDateTimeValue($request->input('receive_date'));
            $receiveNumber = Unit::generateReceiveNumber();

            $import = new UnitsImport($category, $distributor, $assetGroup, $receiveNumber);

            Excel::import($import, $request->file('file'));

            $rowCounts = $import->getRowCount();
            $message = "Import success. Total rows: {$rowCounts['total']}, Rows success: {$rowCounts['successful']}";

            Log::info($message);

            DB::commit();

            return redirect()->back()->with('success', $message);
        } catch (ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $errors = collect($failures)->map(function ($failure) {
                return "Row {$failure->row()}: <strong>{$failure->values()['Serial Number']}</strong> {$failure->errors()[0]}";
            })->implode('<br>');

            Log::error("Import fail. Errors: " . $errors);

            $errormessage = "Import fail. Errors: " . $errors;

            return redirect()->back()->with('error', $errormessage);
        } catch (QueryException $e) {
            DB::rollBack();
            // Log the full error message
            Log::error("Import fail. Error: " . $e->errorInfo[2]);

            $errorMessage = handleDatabaseError($e);

            return redirect()->back()->with(['error' => $errorMessage]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Import fail. Error: " . $e->getMessage());

            return redirect()->back()->with(['error' => "Import fail! "]);
        }
    }

    public function importAll(Request $request)
    {
        if (!$request->hasFile('file-all')) {
            return back()->with('error', 'Please select a file to upload.');
        }

        $request->validate([
            'file-all' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            DB::beginTransaction();

            $receiveNumber = Unit::generateReceiveNumber();

            $import = new UnitsImportFileOnly($receiveNumber);

            Excel::import($import, $request->file('file-all'));

            $rowCounts = $import->getRowCount();
            $message = "Import success. Total rows: {$rowCounts['total']}, Rows success: {$rowCounts['successful']}";

            Log::info($message);

            DB::commit();

            return redirect()->back()->with('success', $message);
        } catch (ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $errors = collect($failures)->map(function ($failure) {
                return "Row {$failure->row()}: <strong>{$failure->values()['Serial Number']}</strong> {$failure->errors()[0]}";
            })->implode('<br>');

            Log::error("Import fail. Errors: " . $e);

            $errormessage = "Import fail. Errors: " . $errors;

            Log::error("Import fail. Errors: " . $errors);

            return redirect()->back()->with('error', $errormessage);
        } catch (QueryException $e) {
            DB::rollBack();
            // Log the full error message
            Log::error("Import fail. Error: " . $e->errorInfo[2]);
            Log::error("Import fail. Error: " . $e);

            $errorMessage = handleDatabaseError($e);

            return redirect()->back()->with(['error' => $errorMessage]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Import fail. Error: " . $e);
            Log::error("Import fail. Error: " . $e->getMessage());

            return redirect()->back()->with(['error' => "Import fail! "]);
        }
    }


    /**
     * Show the form for editing the specified unit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $unit = Unit::with('stagings', 'stagings.monitor')->findOrFail($id);
        $categories = UnitCategory::options();
        $statusOptions = UnitStatus::options();
        $unitMonitor = $unit->category == UnitCategory::MONITOR;

        if ($unitMonitor) {
            $unitDesktop = Unit::with('stagings')->whereHas('stagings', function ($query) use ($unit) {
                $query->where('staging_monitor', $unit->serial);
            })->get();
            if ($unitDesktop) {
                return response()->view('units.edit', compact('unit', 'categories', 'statusOptions', 'unitDesktop'));
            }
        }
        return response()->view('units.edit', compact('unit', 'categories', 'statusOptions'));
    }

    /**
     * Update the specified unit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'serial' => 'required',
            'category' => 'nullable',
            'status' => 'nullable',
            'Brand' => 'nullable',
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update($validatedData);

        return redirect('/inventory')
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified unit from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);

        try {
            $unit->delete();
            return redirect()->back()->with('success', 'Unit deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete unit. ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete unit !!');
        }
    }
}
