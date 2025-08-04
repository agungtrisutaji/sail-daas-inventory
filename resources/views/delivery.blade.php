<x-app-layout>

	<x-slot name="header">
		{{ __('Delivery') }}
	</x-slot>

	<div class="container-fluid">
		<div class="row">
			<div class="col d-flex justify-content-end">
				{{-- <x-import-form class="btn-success mx-2 mb-3"
					:route="route('delivery.import')"
					:title="'Import Deliveries'"
					:buttonText="'Import'"
					:name="'import-delivery'"
					:fields="[
					    [
					        'name' => 'company_id',
					        'label' => 'Company',
					        'type' => 'select',
					        'model' => $companies,
					        'valueField' => 'id',
					        'labelField' => 'company_name',
					    ],
					]"
					method="POST"
					withSelect /> --}}
				<x-action-list class="mb-3"
					:actions="$actions"
					:type="'button'"
					:label="'Create'" />

			</div>
		</div>

		{{-- TODO add expedition, tambah nomor resi --}}
		@include('deliveries.index')

		@include('components.create-modal', ['modalTitle' => 'Delivery Information'])
	</div>
</x-app-layout>
