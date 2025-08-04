<x-app-layout>

	<x-slot name="header">
		{{ __('Tickets') }}
	</x-slot>

	{{-- TODO add expedition, tambah nomor resi --}}
	<div class="container-fluid"
		id="ticketContainer"
		data-url="{{ route('ticket.data') }}">
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
					aria-label="process ticket">

					<x-create-button modal-target="ticketCreateModal">
						Create New Ticket
					</x-create-button>

					{{-- @if ($units->count() > 0) --}}
					{{-- TODO : refactoring controller for batch update --}}
					{{-- <x-import-form class="btn-outline-primary"
							:name="'batch-update-units'"
							:title="'Batch Update'"
							:method="'POST'"
							:route="route('upgrade.preview')"
							:buttonText="'Batch Update'" /> --}}

					<x-export-modal :modal-id="'upgrade-export-modal'"
						:title="'Export Ticket Data'"
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

		@include('tickets.index')

	</div>
	<x-create-modal-v id="ticketCreateModal"
		title="Create New Ticket"
		size="xl">
		<div class="card">
			<x-create-form-v class="p-3"
				action="{{ route('ticket.store') }}">
				@include('tickets.create')
			</x-create-form-v>
		</div>
	</x-create-modal-v>

	@push('js')
		<script type="module"
			src="{{ Vite::asset('resources/js/ticket-init.js') }}"></script>
	@endpush

</x-app-layout>
