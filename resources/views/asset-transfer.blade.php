<x-app-layout>

	<x-slot name="header">
		{{ __('Asset Transfer') }}
	</x-slot>

	<div class="container-fluid"
		id="assetTransferContainer"
		data-url="{{ route('asset-transfer.data') }}">
		<div class="row">

			<div class="col">
				<x-dropdown-filter :fields="[
				    ['name' => 'serial', 'label' => 'Serial Number', 'type' => 'text'],
				    ['name' => 'brand', 'label' => 'Brand', 'type' => 'text'],
				]" />
			</div>

			<div class="col d-flex justify-content-end">

				<div class="btn-group mb-2"
					role="group"
					aria-label="process upgrade">

					<a class="btn btn-outline-primary"
						type="button"
						href="{{ route('asset-transfer.create') }}">Create Request</a>

					<x-export-modal :modal-id="'upgrade-export-modal'"
						:title="'Export Inventory Data'"
						:description="'Customize your export file'"
						:fields="[
						    ['name' => 'serial', 'label' => 'Serial Number', 'type' => 'text'],
						    ['name' => 'brand', 'label' => 'Brand', 'type' => 'text'],
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
				</div>

			</div>
		</div>

		<x-alert type="success" />
		<x-alert type="error"
			color="danger" />

		<hr class="rounded-pill border-3 border opacity-75">

		@include('asset-transfer.index')
		@include('components.create-modal', ['modalTitle' => 'Update Transfer Progress'])

	</div>

	@push('js')
		<script type="module"
			src="{{ Vite::asset('resources/js/asset-transfer-init.js') }}"></script>
	@endpush
</x-app-layout>
