<x-slot name="header">
	{{ __('Edit Deployment') }}
</x-slot>
{{-- <x-slot name="breadcrumb">
        {{ Breadcrumbs::render('deployment.edit', $unit) }}
    </x-slot> --}}

<div class="card">

	@if ($errors->any())
		<div class="alert alert-danger alert-dismissible fade show">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	@php
		$onDelivery = $deployment->status == App\Enums\DeploymentStatus::ON_DELIVERY;
		$disabled = $onDelivery ? 'disabled' : '';
	@endphp

	<form id="deploymentForm"
		action="{{ route('deployment.update-state', $deployment) }}"
		method="POST">
		@csrf
		@method('PUT')
		<div class="card-body">
			<x-alert type="success" />
			<x-alert type="error"
				color="danger" />
			@if (!$onDelivery)
				<div class="row">
					<div class="d-flex justify-content-end">
						<a
							class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
							href="{{ route('deployment.show', $deployment) }}">
							{{ __('Edit') }}
						</a>
					</div>
				</div>
			@endif
			<div class="row">

				<div class="form-group col-md-6 p-2">
					<x-input-label for="unit_serial"
						:value="__('Serial Number')" />
					<x-text-input class="mt-1 block w-full"
						id="unit_serial"
						name="unit_serial"
						type="text"
						disabled
						:value="$deployment->unit_serial"
						required />
					<x-input-error class="mt-2"
						:messages="$errors->get('unit_serial')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="holeder_name"
						:value="__('Holder Name')" />
					<x-text-input class="mt-1 block w-full"
						id="holder_name"
						name="holder_name"
						type="text"
						:disabled="$disabled"
						:readonly="$deployment->staging->holder_name"
						:value="$deployment->staging->holder_name" />
					<x-input-error class="mt-2"
						:messages="$errors->get('holder_name')" />
				</div>

				@if ($deployment->staging->monitor)
					<div class="form-group col-md-6 p-2">
						<x-input-label for="monitor"
							:value="__('Monitor Serial')" />
						<x-text-input class="mt-1 block w-full"
							id="monitor"
							name="monitor"
							type="text"
							:disabled="$disabled"
							:readonly="$deployment->staging->monitor->serial"
							:value="$deployment->staging->monitor->serial" />
						<x-input-error class="mt-2"
							:messages="$errors->get('monitor')" />
					</div>

					<div class="form-group col-md-6 p-2">
						<x-input-label for="approved_by"
							:value="__('Tracking Number')" />
						<x-text-input class="mt-1 block w-full"
							id="tracking_number"
							name="tracking_number"
							type="text"
							value="{{ $deployment->unit->deliveries->first()->tracking_number ? $deployment->unit->deliveries->first()->tracking_number : '' }}"
							disabled />
						<x-input-error class="mt-2"
							:messages="$errors->get('tracking_number')" />
					</div>

					<div class="form-group col-md-6 p-2">
						<x-input-label for="company"
							:value="__('Company')" />
						<select class="form-select"
							id="company-select"
							name="company"
							disabled>
							@if ($deployment->staging->company)
								<option value="{{ $deployment->staging->company->id }}">{{ $deployment->staging->company->company_name }}
								</option>
							@else
								<option value=""></option>
							@endif
							@foreach ($companies as $company)
								<option value="{{ $company->id }}">{{ $company->company_name }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-6 p-2">
						<x-input-label for="address"
							:value="__('Location')" />
						<select class="form-select"
							id="address-select"
							name="address"
							data-placeholder="Select Request Location"
							disabled>
							@if ($deployment->staging)
								<option value="{{ $deployment->staging->company_address }}">
									{{ $deployment->staging->address_location }}
								</option>
							@else
								<option value=""></option>
							@endif
						</select>
					</div>
				@else
					<div class="form-group col-md-6 p-2">
						<x-input-label for="company"
							:value="__('Company')" />
						<select class="form-select"
							id="company-select"
							name="company"
							disabled>
							@if ($deployment->staging->company)
								<option value="{{ $deployment->staging->company->id }}">{{ $deployment->staging->company->company_name }}
								</option>
							@else
								<option value=""></option>
							@endif
							@foreach ($companies as $company)
								<option value="{{ $company->id }}">{{ $company->company_name }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-6 p-2">
						<x-input-label for="address"
							:value="__('Location')" />
						<select class="form-select"
							id="address-select"
							name="address"
							data-placeholder="Select Request Location"
							disabled>
							@if ($deployment->staging)
								<option value="{{ $deployment->staging->company_address }}">
									{{ $deployment->staging->address_location }}
								</option>
							@else
								<option value=""></option>
							@endif
						</select>
					</div>

					<div class="form-group col-md-6 p-2">
						<x-input-label for="approved_by"
							:value="__('Tracking Number')" />
						<x-text-input class="mt-1 block w-full"
							id="tracking_number"
							name="tracking_number"
							type="text"
							value="{{ $deployment->unit->deliveries->first()->tracking_number ? $deployment->unit->deliveries->first()->tracking_number : '' }}"
							disabled />
						<x-input-error class="mt-2"
							:messages="$errors->get('tracking_number')" />
					</div>
				@endif

				<div class="form-group col-md-6 p-2 pt-0">
					<x-input-label for="bast_number"
						:value="__('BAST Number')" />
					<x-text-input class="mt-1 block w-full"
						id="bast_number"
						name="bast_number"
						type="text"
						:value="$deployment->bast_number"
						:readonly="$deployment->bast_number ? true : false"
						:disabled="$disabled" />
					<x-input-error class="mt-2"
						:messages="$errors->get('bast_number')" />
				</div>

				<div class="form-group col-md-6 p-2 pt-0">
					<x-input-label for="ritm_number"
						:value="__('RITM Number')" />
					<x-text-input class="mt-1 block w-full"
						id="ritm_number"
						name="ritm_number"
						type="text"
						:disabled="$disabled"
						:value="$deployment->ritm_number" />
					<x-input-error class="mt-2"
						:messages="$errors->get('ritm_number')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="status"
						:value="__('Status')" />
					<x-select-input name="status"
						:disabled="$disabled"
						:options="$statusOptions"
						:selected="$deployment->status->value ?? ''" />

					<x-input-error class="mt-2"
						:messages="$errors->get('status')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="sla"
						:value="__('SLA')" />
					<input class="form-control mt-1 block w-full"
						type="text"
						value="{{ $deployment->sla }}"
						disabled />
					<x-input-error class="mt-2"
						:messages="$errors->get('sla')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="approved_by"
						:value="__('Approved By')" />
					<x-text-input id="approved_by"
						name="approved_by"
						type="text"
						:disabled="$disabled"
						:value="$deployment->approved_by"
						:readonly="$deployment->approved_by ? true : false" />
					<x-input-error class="mt-2"
						:messages="$errors->get('approved_by')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="bast_date"
						:value="__('BAST Date')" />
					<x-datetime-picker name="bast_date"
						value="{{ $deployment->bast_date ? date('Y-m-d H:i', strtotime($deployment->bast_date)) : '' }}"
						:disabled="$disabled" />
					<x-input-error class="mt-2"
						:messages="$errors->get('bast_date')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="bast_sign_date"
						:value="__('BAST Sign Date')" />
					<x-datetime-picker name="bast_sign_date"
						value="{{ $deployment->bast_sign_date ? date('Y-m-d H:i', strtotime($deployment->bast_sign_date)) : '' }}"
						:disabled="$disabled" />
					<x-input-error class="mt-2"
						:messages="$errors->get('bast_sign_date')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="actual_arrival_date"
						:value="__('Delivery Arrival Date')" />
					<x-datetime-picker name="actual_arrival_date"
						value="{{ $deployment->unit->deliveries->first()->actual_arrival_date ? date('Y-m-d H:i', strtotime($deployment->unit->deliveries->first()->actual_arrival_date)) : '' }}"
						disabled />

					<x-input-error class="mt-2"
						:messages="$errors->get('actual_arrival_date')" />
				</div>

				<div class="mt-2 px-1 py-3">
					<x-input-label for="deployment_note"
						:value="__('Deployment Notes')" />
					<textarea class="form-control"
					 id="editor"
					 name="deployment_note"
					 {{ $disabled }}></textarea>
					<x-input-error class="mt-2"
						:messages="$errors->get('deployment_note')" />
				</div>

			</div>
		</div>
		@if (!$onDelivery)
			<div class="card-footer">
				<div class="row">
					<div class="form-group d-flex justify-content-center">
						<x-success-button>
							{{ __('Update State') }}
						</x-success-button>
					</div>
				</div>
			</div>
		@endif
	</form>
</div>

@push('js')
	<script>
		$('#deploymentForm').on('submit', function(e) {
			e.preventDefault();
			var form = $(this);
			var url = form.attr('action');
			var method = form.attr('method');
			var data = form.serialize();

			$.ajax({
				url: url,
				type: method,
				data: data,
				success: function(response) {
					$('#createModal').modal('hide');
					// Refresh the DataTable
					$('#deployments-table').DataTable().ajax.reload();
				},
				error: function(xhr) {
					// Handle errors
					console.log(xhr.responseText);
				}
			});
		});
	</script>
@endpush
