<x-app-layout>

	<x-slot name="header">
		{{ __('Inventory') }}
	</x-slot>

	<div class="container-fluid"
		id="inventoryContainer"
		data-url="{{ route('inventory.data') }}">
		<div class="row">
			@php

				$currentFilters = request()->only([
				    'serial',
				    'brand',
				    'status',
				    'category',
				    'service_code',
				    'date_column',
				    'start_date',
				    'end_date',
				]);
				$exportUrl = route('inventory.export', $currentFilters);
			@endphp

			<div class="col">
				@if ($units > 0)
					<x-dropdown-filter :fields="[
					    ['name' => 'name', 'label' => 'Serial Number', 'type' => 'text'],
					    ['name' => 'brand_name', 'label' => 'Brand', 'type' => 'text'],
					    ['name' => 'model_name', 'label' => 'Model', 'type' => 'text'],
					    ['name' => 'location_id_friendlyname', 'label' => 'Location', 'type' => 'text'],
                        ['name' => 'daascontact_id_friendlyname', 'label' => 'Contact', 'type' => 'text'],
                        ['name' => 'daascustomer_id_friendlyname', 'label' => 'Cust Company', 'type' => 'text'],
					    ['name' => 'status', 'type' => 'select', 'label' => 'Status', 'options' => $statusOptions],
					    ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => $categories],
					    ['name' => 'customerservice', 'label' => 'Service', 'type' => 'text'],
					    // [
					    //     'name' => 'company_name',
					    //     'label' => 'Company',
					    //     'type' => 'select',
					    //     'valueField' => 'id',
					    //     'labelField' => 'company_name',
					    //     'model' => $companies,
					    // ],
					    ['name' => 'purchase_date', 'label' => 'Purchase Date', 'type' => 'date'],
					]"
						:noReset="true"
						:companyModel="$companies"
						:companyModel="$companies"
						:companyLabel="'Distributor'"
						:countRelations="'units'"
						:parentName="'distributor_id'"
						:parentUrl="'api/fetch-distributors'"
						:hasChild="false" />
				@endif
			</div>

			<div class="col d-flex justify-content-end">
				{{-- <a href="{{ $exportUrl }}" id="exportButton" class="btn btn-outline-info">Export to Excel</a> --}}

				<div class="btn-group import-btn-group mb-2"
					role="group"
					aria-label="process inventory">
					<x-second-import-form class="btn-outline-success"
						:name="'import-units'"
						:method="'POST'"
						:route="route('unit.import-all-in-one')"
						:submitButtonText="'Upload'"
						:otherButton="['text' => 'Template', 'color' => 'warning', 'link' => route('import.template', 'unit_complete')]" />

					<x-import-form class="btn-outline-primary"
						:name="'import-units'"
						:title="'Import New'"
						:fields="[
						    [
						        'name' => 'category',
						        'label' => 'Catgeory',
						        'type' => 'select',
						        'options' => $categories,
						        'valueField' => 'code',
						        'labelField' => 'label',
						    ],
						    [
						        'name' => 'distributor_id',
						        'label' => 'Distributor',
						        'type' => 'select',
						        'model' => $companies,
						        'valueField' => 'id',
						        'labelField' => 'company_name',
						    ],
						]"
						secondModal
						withDate
						withSelect
						withAssetGroup
						:method="'POST'"
						:route="route('unit.import')"
						:submitButtonText="'Upload'"
						:otherButton="['text' => 'Template', 'color' => 'warning', 'link' => route('import.template', 'unit')]" />

					@if ($units > 0)
						<x-import-form class="btn-outline-warning"
							:name="'batch-update-units'"
							:title="'Batch Update'"
							:method="'POST'"
							:route="route('inventory.preview')"
							:submitButtonText="'Upload'"
							:otherButton="['text' => 'Template', 'color' => 'warning', 'link' => route('import.template', 'unit-update')]" />

						<x-export-modal :modal-id="'inventory-export-modal'"
							:title="'Export Inventory Data'"
							:description="'Customize your export file'"
							:fields="[
							    ['name' => 'name', 'label' => 'Serial Number', 'type' => 'text'],
							    ['name' => 'brand_name', 'label' => 'Brand', 'type' => 'text'],
							    ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => $statusOptions],
							    ['name' => 'type', 'label' => 'Type', 'type' => 'text'],
							    ['name' => 'customerservice', 'label' => 'Service', 'type' => 'text'],
							    ['name' => 'daascustomer_id_friendlyname', 'label' => 'Cust Company', 'type' => 'text'],
							]"
							:columns="[
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
							]"
							:export-route="route('inventory.export')" />
					@endif
				</div>

			</div>
		</div>

		<x-alert type="success" />
		<x-alert type="error"
			color="danger" />

		<hr class="rounded-pill border-3 border opacity-75">

		@include('inventories.index')

	</div>

	@push('js')
		<script type="module"
			src="{{ Vite::asset('resources/js/inventory-init.js') }}"></script>
	@endpush
</x-app-layout>
