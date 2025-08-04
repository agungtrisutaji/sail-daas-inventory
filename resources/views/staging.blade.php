<x-app-layout>
	<x-slot name="header">
		{{ __('Staging') }}
	</x-slot>

	<div class="container-fluid"
		id="stagingContainer"
		data-url="{{ route('staging.data') }}">
		<div class="row">
			<x-dropdown-filter :formId="'filter-form'"
				:fields="[
				    ['name' => 'serial', 'label' => 'Serial Number', 'type' => 'text'],
				    ['name' => 'brand', 'label' => 'Brand', 'type' => 'text'],
				    ['name' => 'status', 'type' => 'select', 'label' => 'Status', 'options' => $statusOptions],
				    ['name' => 'service_code', 'label' => 'Service', 'type' => 'select', 'model' => $services],
				    [
				        'name' => 'is_deployed',
				        'label' => 'Deployed',
				        'type' => 'select',
				        'options' => [['value' => 1, 'label' => 'Deployed'], ['value' => 0, 'label' => 'Not Deployed']],
				    ],
				]"
				:noReset="true"
				:dateRange="true"
				:companyModel="$companies"
				:companyModel="$companies"
				:companyLabel="'Distributor'" />

			<div class="col d-flex justify-content-end">

				<x-import-staging class="btn-primary my-2"
					:name="'import-stagings'"
					:title="'Import Stagings'"
					:method="'POST'"
					:parentUrl="'api/fetch-operational-units'"
					withOperationalUnit
					:route="route('staging.upload.preview')"
					:submitButtonText="'Import'"
					:otherButton="['text' => 'Template', 'color' => 'warning', 'link' => route('import.template', 'staging')]" />
			</div>
		</div>

		<x-alert type="success" />
		<x-alert type="error"
			color="danger" />

		<hr class="rounded-pill border-3 border opacity-75">

		@include('stagings.index')
		@include('components.create-modal', ['modalTitle' => 'Update Staging'])

	</div>

	@push('js')
		<script type="module"
			src="{{ Vite::asset('resources/js/staging-init.js') }}"></script>
	@endpush
</x-app-layout>
