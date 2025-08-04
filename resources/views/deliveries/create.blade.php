<x-app-layout>

	<x-slot name="header">
		{{ __('Create Delivery') }}
	</x-slot>
	<div class="container"
		id="deliveryCreateContainer"
		data-url="{{ route('delivery.data', $itemType) }}">

		<div class="card">
			@if (session()->has('success'))
				<x-alert type="success">
					{{ session()->get('success') }}
				</x-alert>
			@endif

			@if (session()->has('error'))
				<x-alert type="error"
					color="danger">
					{{ session()->get('error') }}
				</x-alert>
			@endif

			<div class="card-header">
				<h3 class="mt-2 text-center">New Delivery Order</h3>
			</div>

			<form class="p-3"
				action="{{ route('delivery.store') }}"
				method="POST">
				@csrf

				<div class="row mt-4">
					<label class="col-form-label text-nowrap col-md-2 required"
						for="tracking_number">Tracking Number</label>
					<div class="col-md-6">
						<input class="form-control"
							id="tracking_number"
							name="tracking_number"
							type="text"
							value="{{ old('tracking_number') }}"
							required>
					</div>
					<label class="col-form-label text-nowrap col-md-2 required text-end"
						for="tracking_number">Delivery For</label>
					<div class="col-md-2">
						<input class="form-control"
							id="delivery_category"
							name="delivery_category"
							type="text"
							value="{{ strtoupper($itemType) }}"
							{{-- TODO: from delivery category enum --}}
							readonly
							required>
					</div>
				</div>

				<div class="row mt-3">
					<div class="col-md-6">
						<div class="form-group">
							<x-input-label class="required"
								for="datetimepickerInput"
								:value="__('Delivery Date')" />

							<x-datetime-picker name="delivery_date"
								value="{{ old('delivery_date') }}"
								required />
							<x-input-error class="mt-2"
								:messages="$errors->get('delivery_date')" />

						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<x-input-label class="required"
								for="datetimepickerInput"
								:value="__('Estimated Arrival Date')" />

							<x-datetime-picker name="estimated_arrival_date"
								value="{{ old('estimated_arrival_date') }}"
								required />
							<x-input-error class="mt-2"
								:messages="$errors->get('estimated_arrival_date')" />
						</div>
					</div>
				</div>

				<div class="row mt-3">
					<x-dynamic-select class="col-md-6"
						:model="$couriers"
						:valueField="'id'"
						:label1="'Courier'"
						:labelField="'name'"
						:parentName="'courier_id'"
						:parentUrl="'api/fetch-couriers'"
						:childUrl="'api/fetch-delivery-services'"
						:children="[
						    [
						        'name' => 'delivery_service_id',
						        'label' => 'Delivery Service',
						        'childValue' => 'id',
						        'childLabel' => 'name',
						        'inputType' => 'select',
						    ],
						]"
						required />

				</div>

				@if ($itemType !== 'staging')
					<div class="row">
						<x-dynamic-select class="col-md-6 p-2"
							:model="$companies"
							:valueField="'id'"
							:label1="'Company'"
							:labelField="'company_name'"
							:labelCount="true"
							:countRelations="'addresses'"
							:parentName="'company_id'"
							:parentUrl="'api/fetch-companies'"
							:childUrl="'api/fetch-locations'"
							:children="[
							    [
							        'name' => 'company_address',
							        'label' => 'Location',
							        'childValue' => 'id',
							        'childLabel' => 'location',
							        'inputType' => 'select',
							    ],
							]"
							required />
					</div>
				@endif

				<div class="mt-3 py-3 ps-1">
					<x-input-label for="unit_notes"
						:value="__('Remarks')" />
					<textarea class="form-control"
					 id="editor"
					 name="unit_notes"></textarea>
					<x-input-error class="mt-2"
						:messages="$errors->get('unit_notes')" />
				</div>

				<div class="row mt-3">
					<div class="col d-flex justify-content-start">

						<a class="btn btn-secondary"
							href="{{ route('delivery') }}">Back</a>
					</div>
					<div class="col d-flex justify-content-end">

						<button class="btn btn-primary"
							type="submit">Create Delivery</button>
					</div>
				</div>

				<div class="form-group my-3">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title mt-2 text-center">List of Delivery Items</h5>
						</div>
						<div class="card-body px-1">

							<x-data-table :id="'deliveryItemTable'"
								:columns="[
								    '#',
								    'Serial Number',
								    'Monitor Serial Number',
								    'Category',
								    'Service',
								    'Model',
								    'Brand',
								    'Company',
								    'Location',
								    'Created At',
								    'Update At',
								]">
							</x-data-table>
						</div>
					</div>
				</div>

			</form>
		</div>
	</div>

	@push('js')
		<script type="module"
			src="{{ Vite::asset('resources/js/delivery-create-init.js') }}"></script>
	@endpush
</x-app-layout>
