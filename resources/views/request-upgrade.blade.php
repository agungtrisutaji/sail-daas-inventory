<x-app-layout>

	<x-slot name="header">
		{{ __('Request Upgrade') }}
	</x-slot>

	{{-- TODO add expedition, tambah nomor resi --}}
	<div class="container-fluid"
		id="requestUpgradeContainer"
		data-url="{{ route('upgrade.data') }}">
		<div class="row">

			<div class="col">
				{{-- @if ($units->count() > 0) --}}
				<x-dropdown-filter :fields="[
				    ['name' => 'serial', 'label' => 'Serial Number', 'type' => 'text'],
				    ['name' => 'brand', 'label' => 'Brand', 'type' => 'text'],
				    // ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => $statusOptions],
				    // ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'options' => $categories],
				    // ['name' => 'service_code', 'label' => 'Service', 'type' => 'select', 'model' => $services],
				    // ['name' => 'sla', 'label' => 'SLA', 'type' => 'select', 'options' => $slaOptions],
				]"
					{{-- :dateRange="true" --}}
					{{-- :dateOptions="$dateOptions" --}} />
				{{-- @endif --}}
			</div>

			<div class="col d-flex justify-content-end">
				{{-- <a href="{{ $exportUrl }}" id="exportButton" class="btn btn-outline-info">Export to Excel</a> --}}

				<div class="btn-group mb-2"
					role="group"
					aria-label="process upgrade">

					{{-- <x-create-button modal-target="requestCreateModal">
						Create New Request
					</x-create-button> --}}

					<a class="btn btn-outline-primary"
						type="button"
						href="{{ route('upgrade.create') }}">Create Request</a>

					{{-- @if ($units->count() > 0) --}}
					{{-- TODO : refactoring controller for batch update --}}
					{{-- <x-import-form class="btn-outline-primary"
							:name="'batch-update-units'"
							:title="'Batch Update'"
							:method="'POST'"
							:route="route('upgrade.preview')"
							:buttonText="'Batch Update'" /> --}}

					<x-export-modal :modal-id="'upgrade-export-modal'"
						:title="'Export Inventory Data'"
						:description="'Customize your export file'"
						:fields="[
						    ['name' => 'serial', 'label' => 'Serial Number', 'type' => 'text'],
						    ['name' => 'brand', 'label' => 'Brand', 'type' => 'text'],
						    // ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => $statusOptions],
						    // ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'options' => $categories],
						    // ['name' => 'service_code', 'label' => 'Service', 'type' => 'select', 'model' => $services],
						    // ['name' => 'sla', 'label' => 'SLA', 'type' => 'select', 'options' => $slaOptions],
						]"
						:columns="[
						    'Serial Number',
						    'Brand',
						    'Model',
						    'Status',
						    'Service',
						    'Category',
						    'Location',
						    'Company',
						    'Address',
						    'SLA',
						    'Notes',
						]"
						:export-route="route('upgrade.export')" />
					{{-- @endif --}}
				</div>

			</div>
		</div>

		<x-alert type="success" />
		<x-alert type="error"
			color="danger" />

		<hr class="rounded-pill border-3 border opacity-75">

		@include('request-upgrades.index')

	</div>
	{{-- <x-create-modal-v id="requestCreateModal"
		title="Create New Request"
		size="xl">
		<x-create-form-v action="{{ route('upgrade.store') }}">
			@include('request-upgrades.create')
		</x-create-form-v>
	</x-create-modal-v> --}}

	@push('js')
		<script type="module"
			src="{{ Vite::asset('resources/js/request-upgrade-init.js') }}"></script>
	@endpush

</x-app-layout>
