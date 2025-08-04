<?php

use App\Http\Controllers\AssetTransferController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DeployController;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestUpgradeController;
use App\Http\Controllers\StagingController;
use App\Http\Controllers\TerminationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UnitController;
use App\Models\Address;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware('auth')->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard',  action: 'index')->name('dashboard');
        Route::get('api/unit-status-chart',  action: 'getUnitStatusChart');
        Route::get('api/available-units-by-model',  'getAvailableUnitsByModel');
        // routes/api.php
        Route::get('/staging-chart',  'stagingChart');
        Route::get('/delivery-chart',  'deliveryChart');
        Route::get('/deployment-chart',  'deploymentChart');
    });

    Route::controller(UnitController::class)->group(function () {
        Route::get('unit', 'index')->name('unit');
        Route::get('unit/edit/{id}', 'edit')->name('unit.edit');
        Route::put('unit/edit/{id}', 'update')->name('unit.update');
        Route::delete('unit/{id}', 'destroy')->name('unit.destroy');

        Route::post('unit/import/excel', 'import')->name('unit.import');
        Route::post('unit/import/excel-all-in-one', 'importAll')->name('unit.import-all-in-one');
        Route::get('unit/export/excel', 'export')->name('unit.export');
    });

    Route::controller(InventoryController::class)->group(function () {
        Route::get('inventory', 'index')->name('inventory');
        Route::get('api/inventory', 'getData')->name('inventory.data');

        Route::post('inventory/batch/preview', 'preview')->name('inventory.preview');
        Route::get('inventory/batch/preview', 'getPreviewData')->name('inventory.preview.data');
        Route::post('inventory/batch/update', 'batchUpdate')->name('inventory.batch.update');

        Route::get('inventory/export/excel', 'export')->name('inventory.export');
    });

    Route::controller(StagingController::class)->group(function () {
        Route::get('staging', 'index')->name('staging');
        Route::get('api/staging', 'getData')->name('staging.data');

        Route::get('staging/{staging}', 'show')->name('staging.show');
        Route::put('staging/edit/{staging}', 'update')->name('staging.update');

        Route::get('staging/edit/{staging}', 'edit')->name('staging.edit');
        Route::put('staging/update-state/{staging}', 'updateState')->name('staging.update-state');

        Route::delete('staging/{staging}', 'destroy')->name('staging.destroy');

        Route::get('staging/export/excel', 'export')->name('staging.export');
        Route::post('/staging/upload-preview', 'uploadPreview')->name('staging.upload.preview');
        Route::get('/staging/preview-data', 'getPreviewData')->name('staging.preview.data');
        Route::post('/staging/import-process', 'processImport')->name('staging.import.process');
    });

    Route::controller(DeliveryController::class)->group(function () {
        Route::get('delivery', 'index')->name('delivery');
        Route::get('api/delivery/{type}', 'getData')->name('delivery.data');

        Route::get('delivery/create/{type}', 'create')->name('delivery.create');
        Route::post('delivery/create', 'store')->name('delivery.store');
        Route::get('delivery/{delivery}', 'show')->name('delivery.show');
        Route::patch('delivery/{delivery}/mark-as-delivered', 'markAsDelivered')->name('delivery.mark-as-delivered');
        Route::post('delivery/import', 'import')->name('delivery.import');
    });

    Route::controller(DeploymentController::class)->group(function () {
        Route::get('deployment', 'index')->name('deployment');
        Route::get('api/deployment', 'getData')->name('deployment.data');

        Route::get('deployment/edit/{deployment}', 'edit')->name('deployment.edit');
        Route::put('deployment/edit/{deployment}', 'update')->name('deployment.update');

        Route::get('deployment/{deployment}', 'show')->name('deployment.show');
        Route::put('deployment/update-state/{deployment}', 'updateState')->name('deployment.update-state');

        Route::delete('deployment/{deployment}', 'destroy')->name('deployment.destroy');

        Route::get('deployment/export/excel', 'export')->name('deployment.export');
        Route::post('/deployment/upload-preview', 'uploadPreview')->name('deployment.upload.preview');
        Route::post('/deployment/import-process', 'processImport')->name('deployment.import.process');
    });


    Route::controller(TerminationController::class)->group(function () {
        Route::get('termination', 'index')->name('termination');
        Route::get('api/termination', 'getData')->name('termination.data');

        Route::get('termination/create', 'create')->name('termination.create');
        Route::post('termination/create', 'store')->name('termination.store');


        Route::get('termination/{termination}', 'show')->name('termination.show');
        Route::put('termination/update/{termination}', 'update')->name('termination.update');

        Route::get('termination/edit/{termination}', 'edit')->name('termination.edit');
        Route::put('termination/update-state/{termination}', 'updateState')->name('termination.update-state');

        Route::delete('termination/{termination}', 'destroy')->name('termination.destroy');
    });


    Route::controller(RequestUpgradeController::class)->group(function () {
        Route::get('upgrade', 'index')->name('upgrade');
        Route::get('api/upgrade', 'getData')->name('upgrade.data');

        Route::get('upgrade/create', 'create')->name('upgrade.create');
        Route::post('upgrade/create', 'store')->name('upgrade.store');

        Route::get('upgrade/edit/{upgrade}', 'edit')->name('upgrade.edit');

        Route::put('upgrade/edit/{upgrade}', 'update')->name('upgrade.update');

        Route::delete('upgrade/{upgrade}', 'destroy')->name('upgrade.destroy');

        Route::get('upgrade/export/excel', 'export')->name('upgrade.export');
        Route::post('/upgrade/upload-preview', 'uploadPreview')->name('upgrade.upload.preview');
        Route::post('/upgrade/import-process', 'processImport')->name('upgrade.import.process');
    });

    Route::controller(TicketController::class)->group(function () {
        Route::get('ticket', 'index')->name('ticket');
        Route::get('ticket/create', 'create')->name('ticket.create');
        Route::post('ticket/create', 'store')->name('ticket.store');
        Route::get('api/ticket', 'getData')->name('ticket.data');
        Route::get('ticket/edit/{ticket}', 'edit')->name('ticket.edit');
        Route::put('ticket/edit/{ticket}', 'update')->name('ticket.update');
        Route::delete('ticket/{ticket}', 'destroy')->name('ticket.destroy');

        Route::get('ticket/export/excel', 'export')->name('ticket.export');
        Route::post('/ticket/upload-preview', 'uploadPreview')->name('ticket.upload.preview');
        Route::post('/ticket/import-process', 'processImport')->name('ticket.import.process');
    });

    Route::controller(AssetTransferController::class)->group(function () {
        Route::get('asset-transfer', 'index')->name('asset-transfer');
        Route::get('api/asset-transfer', 'getData')->name('asset-transfer.data');

        Route::get('asset-transfer/create', 'create')->name('asset-transfer.create');
        Route::post('asset-transfer/create', 'store')->name('asset-transfer.store');


        Route::get('asset-transfer/{assetTransfer}', 'show')->name('asset-transfer.show');
        Route::put('asset-transfer/update/{assetTransfer}', 'update')->name('asset-transfer.update');

        Route::get('asset-transfer/edit/{assetTransfer}', 'edit')->name('asset-transfer.edit');
        Route::put('asset-transfer/update-state/{assetTransfer}', 'updateState')->name('asset-transfer.update-state');

        Route::delete('asset-transfer/{assetTransfer}', 'destroy')->name('asset-transfer.destroy');
    });

    Route::controller(ImportController::class)->group(function () {
        Route::get('import/download-template/{type}', 'importTemplate')->name('import.template');
    });

    Route::controller(DropdownController::class)->group(function () {
        Route::post('api/fetch-companies', 'fetchCompanies')->name('api.fetch-companies');
        Route::post('api/fetch-distributors', 'fetchDistributors')->name('api.fetch-distributros');
        Route::post('api/fetch-locations', 'fetchCompanyLocation')->name('api.fetch-locations');
        Route::post('api/fetch-to-locations', 'fetchToCompanyLocation')->name('api.fetch-to-locations');

        Route::post('api/fetch-operational-units', 'fetchOperationalUnits')->name('api.fetch-operational-units');
        Route::post('api/fetch-operational-locations', 'fetchOperationalLocation')->name('api.fetch-operational-locations');

        Route::post('api/fetch-units/deployment', 'fetchUnitDeployment')->name('api.fetch-units.deployment');
        Route::post('api/fetch-units/staging', 'fetchUnitStaging')->name('api.fetch-units.staging');
        Route::post('api/fetch-unit-serials', 'fetchUnitSerials')->name('api.fetch-unit-serials');

        Route::post('api/fetch-couriers', 'fetchCouriers')->name('api.fetch-couriers');
        Route::post('api/fetch-delivery-services', 'fetchdDeliveryService')->name('api.fetch-delivery-services');
    });

    Route::get('/api/get-addresses/{companyId}', function ($companyId) {
        $addresses = Address::where('company_id', $companyId)->get(['id', 'location']);
        return response()->json($addresses);
    });

    Route::get('claim', [ClaimController::class, 'index'])->name('claim');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('test', function () {
    return response('Tesst OK', 200);
});

require __DIR__ . '/auth.php';
