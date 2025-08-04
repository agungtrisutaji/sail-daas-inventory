<x-app-layout>

	<x-slot name="header">
		{{ __('Unit Sold') }}
	</x-slot>

	<div class="container-fluid"
		id="saleContainer"
		data-url="{{ route('sale.data') }}">
		<div class="row">
			<div class="col">
				@if ($units > 0)
					<x-dropdown-filter :fields="[
					    ['name' => 'serial', 'label' => 'Serial Number', 'type' => 'text'],
					    ['name' => 'brand', 'label' => 'Brand', 'type' => 'text'],
					    ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'options' => $categories],
					]"
						:noReset="true"
						:dateRange="true"
						:dateOptions="$dateOptions"
						:hasChild="false" />
				@endif
			</div>

			<div class="col d-flex justify-content-end">
				{{-- <a href="{{ $exportUrl }}" id="exportButton" class="btn btn-outline-info">Export to Excel</a> --}}

				<div class="btn-group import-btn-group mb-2"
					role="group"
					aria-label="process sale">
					<x-import-form class="btn-outline-primary"
						:name="'import-sales'"
						:title="'Import New'"
						:fields="[
						    [
						        'name' => 'document',
						        'label' => 'Document Number',
						        'type' => 'text',
						    ],
						    [
						        'name' => 'sales_name',
						        'label' => 'Sales Name',
						        'type' => 'text',
						    ],
						]"
						withDate
						withAssetGroup
						:method="'POST'"
						:route="route('sale.import')"
						:submitButtonText="'Upload'"
						:otherButton="['text' => 'Template', 'color' => 'warning', 'link' => route('import.template', 'sale')]" />

					@if ($units > 0)
						<x-export-modal :modal-id="'sale-export-modal'"
							:title="'Export sale Data'"
							:description="'Customize your export file'"
							:fields="[
							    ['name' => 'serial', 'label' => 'Serial Number', 'type' => 'text'],
							    ['name' => 'brand', 'label' => 'Brand', 'type' => 'text'],
							    ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'options' => $categories],
							]"
							dateRange
							:dateOptions="$dateOptions"
							:columns="['Serial Number', 'Brand', 'Model', 'Category', 'Notes']"
							:export-route="route('sale.export')" />
					@endif
				</div>

			</div>
		</div>

		<x-alert type="success" />
		<x-alert type="error"
			color="danger" />

		<hr class="rounded-pill border-3 border opacity-75">

		@include('sales.index')

	</div>

	@push('js')
		<script type="module"
			src="{{ Vite::asset('resources/js/sales-init.js') }}"></script>
	@endpush
</x-app-layout>
