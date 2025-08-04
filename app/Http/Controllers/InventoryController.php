<?php

namespace App\Http\Controllers;

use App\Enums\CompanyCategory;
use App\Enums\UnitCategory;
use App\Enums\UnitStatus;
use App\Exports\InventoryExport;
use App\Imports\UnitsPreview;
use App\Models\Company;
use App\Models\Service;
use App\Models\Unit;
use App\Traits\HasDeleteTempFile;
use App\Traits\HasValidateEnumValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller
{
    use HasValidateEnumValue, HasDeleteTempFile;
    public function index()
    {
        $dateOptions = [
            'created_at' => 'Created',
            'updated_at' => 'Last Updated',
            'staging_start' => 'Staging Start',
            'staging_finish' => 'Staging Finish',
        ];

        $statusOptions = [
            'stock' => 'Stock',
            'implementation' => 'Implementation',
            'production' => 'Production',
            'obsolete' => 'Obsolete',
        ];

        $categories = UnitCategory::options();
        $slaOptions = [
            'Meet' => 'Meet',
            'Breach' => 'Breach',
        ];

        $services = Service::select('label', 'code')->get();

        $companies = Company::select('company_name', 'id')->where('company_category', CompanyCategory::DISTRIBUTOR)->orWhere('company_name', '=', 'PT MACRO TREND TECHNOLOGY')->get();

        $units = Unit::select('id')->count('id');

        return view('inventory', compact('statusOptions', 'services', 'slaOptions', 'dateOptions', 'categories', 'units', 'companies'));
    }

    public function apiRequest($request, $pagination = false)
    {
        $jsonData = $this->configurePayload($request, $pagination);

        // Kirim request ke iTop (REST/JSON)
        $response = Http::asForm()->post(config('app.itop.url'), [
            'auth_user' => config('app.itop.user'),
            'auth_pwd'  => config('app.itop.password'),
            'json_data' => json_encode($jsonData),
        ]);
        return $response->json();
    }

    public function configurePayload($request, $pagination)
    {
        // Ambil parameter DataTables
        $start  = $request->input('start', 0);
        $length = $request->input('length');

        if ($pagination) {
            // Jika tidak ada pagination, gunakan default 10
            $length = $length ?: 10;
        } else {
            // Jika tidak ada pagination, set length ke -1 untuk mengambil semua data
            $length = -1;
        }

        // Paginasi: hitung page dan limit
        $page  = floor($start / $length) + 1;
        $limit = $length;

        // Bangun WHERE OQL dari parameter request
        $where = $this->buildOqlWhere($request);

        return [
            'operation'     => 'core/get',
            'class'         => 'PC',
            'key'           => "SELECT PC{$where}",
            'output_fields' => 'id, name, brand_name, model_name, status, type, asset_number, customerservice, organization_name, purchase_date, location_id_friendlyname, daascontact_id_friendlyname, daascustomer_id_friendlyname, description',
            'limit'         => (string)$limit,
            'page'          => (string)$page,
        ];
    }

    public function proceedData($result)
    {
        // Proses hasil menjadi format DataTables
        $objects = $result['objects'] ?? [];
        $data = [];
        foreach ($objects as $obj) {
            $data[] = $obj['fields'];
        }
        return $data;
    }

    public function getData(Request $request)
    {
        $draw   = $request->input('draw');

        $result = $this->apiRequest($request, true);

        $data = $this->proceedData($result);

        // get number from "message": "Found: number",
        $count = $result['message'] ? (int)filter_var($result['message'], FILTER_SANITIZE_NUMBER_INT) : 0;

        Log::info("DataTables response: " . json_encode($data, JSON_PRETTY_PRINT));

        // Kembalikan JSON ke DataTables
        return response()->json([
            'draw'            => intval($draw),
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => $data,
        ]);
    }

    protected function buildOqlWhere(Request $request): string
    {
        $filterFields = [
            'name'                     => 'name',
            'brand_name'               => 'brand_name',
            'model_name'               => 'model_name',
            'status'                   => 'status',
            'type'                     => 'type',
            'asset_number'             => 'asset_number',
            'customerservice'          => 'customerservice',
            'organization_name'        => 'organization_name',
            'purchase_date'            => 'purchase_date',
            'location_id_friendlyname' => 'location_id_friendlyname',
            'daascontact_id_friendlyname' => 'daascontact_id_friendlyname',
            'daascustomer_id_friendlyname' => 'daascustomer_id_friendlyname',
        ];

        $conditions = [];

        foreach ($filterFields as $input => $field) {
            $value = $request->input($input);
            if (!empty($value)) {
                // Escape single quotes to prevent breaking OQL
                $escaped = str_replace("'", "\\'", $value);
                $conditions[] = "$field LIKE '%{$escaped}%'";
            }
        }

        Log::info("OQL conditions: " . json_encode($conditions, JSON_PRETTY_PRINT));

        return count($conditions) ? ' WHERE ' . implode(' AND ', $conditions) : '';
    }

    private function getSerialAction($data)
    {
        return view('components.action', [
            'url' => route('unit.edit', $data->id),
            'label' => $data->serial,
        ])->render();
    }

    private function getTicketStatusBadge($data)
    {
        if (!$data->ticket) {
            return null;
        }
        return view('components.status-badge', ['status' => $data->ticket->status])->render();
    }

    private function getActionColumn($data)
    {
        $actions = [
            'edit' => [
                'url' => route('unit.edit', $data->id),
                'label' => 'Edit',
            ],
            'destroy' => [
                'url' => route('unit.destroy', $data->id),
                'label' => 'Cancel',
                'method' => 'DELETE',
                'confirm' => 'Are you sure you want to cancel this unit?',
            ],
        ];
        return view('components.action-list', ['actions' => $actions, 'type' => 'anchor'])->render();
    }

    private function getStatusBadge($data)
    {
        return view('components.status-badge', ['status' => $data->status])->render();
    }

    public function export(Request $request)
    {
        $validColumns = [
            'Serial Number',
            'Cust Company',
            'Brand',
            'Model',
            'Status',
            'Service',
            'Type',
            'Location',
            'Purchase Date',
            'Asset Number',
            'Notes',
        ];

        $fileName = $request->input('file_name');
        if (empty($fileName)) {
            $fileName = 'Inventory';
        }
        $columns = $request->input('columns', []);
        $columns = array_intersect($validColumns, $columns);

        if (empty($columns)) {
            $columns = $validColumns;  // Use all columns if none selected
        }

        //TODO : fix this,  Allowed memory size of 134217728 bytes exhausted (tried to allocate 2097160 bytes)


        $result = $this->apiRequest($request);

        $filteredQuery = $this->proceedData($result);

        return Excel::download(new InventoryExport($filteredQuery, $columns), $fileName  . '_' . Carbon::now() . '.xlsx');
    }

    public function preview(Request $request)
    {
        $file = $request->file('file');
        $data = Excel::toCollection(new UnitsPreview(), $file)->first();

        $path = $file->store('temp');
        $fullPath = storage_path('app/' . $path);

        $request->session()->put('temp_file_path', $fullPath);
        Log::info("File uploaded and path stored in session: {$fullPath}");

        $requiredColumns = ['Serial Number'];
        $optionalColumns = ['Device Status', 'Category', 'Note', 'Company', 'Distributor'];

        // Check if all required columns are present
        $missingColumns = array_diff($requiredColumns, $data->first()->keys()->toArray());

        if (!empty($missingColumns)) {
            return response()->json(['error' => 'Missing required columns: ' . implode(', ', $missingColumns)], 400);
        }

        $companies = Company::all()->pluck('company_name', 'id');

        $processedData = $data->map(function ($row, $index) use ($optionalColumns, $companies) {
            $rowNumber = $index + 2;
            $serialNumber = $row['Serial Number'];
            $messages = [];

            if (!$serialNumber) {
                $messages[] = "Serial Number is missing.";
            } else {
                $unit = Unit::where('serial', $serialNumber)->first();
                if (!$unit) {
                    $messages[] = "Serial Number '{$serialNumber}' not found.";
                }
            }

            // Process optional columns if present
            foreach ($optionalColumns as $column) {
                if (isset($row[$column])) {
                    if ($column === 'Device Status' && $row[$column] !== null && $row[$column] !== '') {
                        try {
                            $mappedStatus = $this->validateAndGetEnumValue($row[$column], UnitStatus::class, 'Device Status');
                            $row[$column] = $mappedStatus;
                        } catch (\Exception $e) {
                            $messages[] = $e->getMessage();
                        }
                    } elseif ($column === 'Category' && $row[$column] !== null && $row[$column] !== '') {
                        try {
                            $mappedCategory = $this->validateAndGetEnumValue($row[$column], UnitCategory::class, 'Category');
                            $row[$column] = $mappedCategory;
                        } catch (\Exception $e) {
                            $messages[] = $e->getMessage();
                        }
                    } elseif ($column === 'Company' && $row[$column] !== null && $row[$column] !== '') {
                        if (!$companies->contains($row[$column])) {
                            $messages[] = "Invalid company name: {$row[$column]}";
                        }
                    } elseif ($column === "Distributor" && $row[$column] !== null && $row[$column] !== '') {
                        if (!$companies->contains($row[$column])) {
                            $messages[] = "Invalid distributor name: {$row[$column]}";
                        }
                    }
                }
            }

            $row['message'] = implode(' ', $messages);
            $row['row_number'] = $rowNumber;
            return $row;
        });

        // Store processed data in the session
        $request->session()->put('preview_data', $processedData);

        $hasErrorMessage = $processedData->contains(function ($row) {
            return !empty($row['message']);
        });

        return view('units.preview', [
            'data' => $processedData,
            'hasErrorMessage' => $hasErrorMessage
        ]);
    }

    public function getPreviewData(Request $request)
    {
        $data = $request->session()->get('preview_data');
        $distributors = Company::where('company_category', CompanyCategory::DISTRIBUTOR)->get();

        return DataTables::of($data)
            ->addColumn('row_number', function ($row) {
                return $row['row_number'];
            })
            ->addColumn('status', function ($row) {
                if (!empty($row['message'])) {
                    return '<span class="text-danger">' . $row['message'] . '</span>';
                } else {
                    return '<span class="text-success">Valid</span>';
                }
            })
            ->addColumn('Device Status', function ($data) {
                $status = $data['Device Status'] ?? null;
                return $status ? view('components.status-badge', ['status' => $data['Device Status']])->render() : '';
            })
            ->addColumn('Category', function ($data) {
                $category = $data['Category'] ?? null;
                return $category ? view('components.status-badge', ['status' => $data['Category']])->render() : '';
            })
            ->editColumn('Note', function ($row) {
                $note = $row['Note'] ?? '';
                if (strlen($note) > 50) {
                    return '<span title="' . e($note) . '">' . e(substr($note, 0, 50)) . '...</span>';
                }
                return e($note);
            })
            ->rawColumns(['status', 'Note', 'Device Status', 'Category', 'Distributor'])
            ->make(true);
    }

    private function prepareUpdateData($row, $rowNumber)
    {
        $updateData = [];

        // Process optional fields
        $optionalFields = [
            'Device Status' => ['field' => 'status', 'enum' => UnitStatus::class],
            'Category' => ['field' => 'category', 'enum' => UnitCategory::class],
            'Company' => ['field' => 'company_id', 'value' => 'id', 'model' => Company::class, 'model_column' => 'company_name'],
            'Distributor' => ['field' => 'distributor_id', 'value' => 'id', 'model' => Company::class, 'model_column' => 'company_name'],
            'Brand' => ['field' => 'brand'],
            'Model' => ['field' => 'model'],
            'Monitor Serial Number' => ['field' => 'monitor_serial'],
            'Monitor Model' => ['field' => 'monitor_model'],
            'Note' => ['field' => 'note'],
        ];

        foreach ($optionalFields as $excelColumn => $details) {
            if (isset($row[$excelColumn]) && $row[$excelColumn] !== '') {
                if (isset($details['enum'])) {
                    $value = $this->validateAndGetEnumValue($row[$excelColumn], $details['enum'], $excelColumn);
                    if ($value !== null) {
                        $updateData[$details['field']] = $value;
                    } else {
                        Log::warning("Row {$rowNumber}: Invalid {$excelColumn}");
                    }
                } elseif (isset($details['model']) && isset($details['model_column'])) {
                    $model = $details['model']::where($details['model_column'], $row[$excelColumn])->first();
                    if ($model) {
                        $updateData[$details['field']] = $model->id;
                    } else {
                        Log::warning("Row {$rowNumber}: Invalid {$excelColumn}");
                    }
                } else {
                    $updateData[$details['field']] = $row[$excelColumn];
                }
            }
        }

        return $updateData;
    }

    public function batchUpdate(Request $request)
    {
        $filePath = $request->session()->get('temp_file_path');

        if (!$filePath || !file_exists($filePath)) {
            return redirect()->route('inventory')->with('error', 'File is not found. Please upload again.');
        }

        $data = Excel::toCollection(new UnitsPreview(), $filePath)->first();

        DB::beginTransaction();

        try {
            $updatedCount = 0;
            $errorCount = 0;

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2;
                $serialNumber = $row['Serial Number'];
                $unit = Unit::where('serial', $serialNumber)->first();

                if (!$unit) {
                    Log::warning("Row {$rowNumber}: Unit with Serial Number '{$serialNumber}' not found.");
                    $errorCount++;
                    continue;
                }

                $updateData = $this->prepareUpdateData($row, $rowNumber);

                if (empty($updateData)) {
                    $errorCount++;
                    continue;
                }

                $unit->update($updateData);
                $updatedCount++;
            }

            DB::commit();
            $this->deleteTempFile($filePath, $request);

            return redirect()->route('inventory')->with('success', "Update success. {$updatedCount} unit(s) updated. {$errorCount} unit(s) skipped.");
        } catch (\Exception $e) {

            DB::rollBack();
            $this->deleteTempFile($filePath, $request);
            Log::error("Error during update process: " . $e);
            return redirect()->route('inventory')->with('error', 'Error during update process. Please try again.');
        }
    }
}
