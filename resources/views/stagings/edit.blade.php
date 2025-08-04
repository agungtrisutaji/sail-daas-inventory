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

	<form id="stagingForm"
		action="{{ route('staging.update-state', $staging) }}"
		method="POST">
		@csrf
		@method('PUT')
		<div class="card-body pt-1">
			<x-alert type="success" />
			<x-alert type="error"
				color="danger" />
			<div class="row">
				<div class="d-flex justify-content-end">
					<a
						class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
						href="{{ route('staging.show', $staging) }}">
						{{ __('Edit') }}
					</a>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-6 p-2 pt-0">
					<x-input-label for="unit_serial"
						:value="__('Serial Number')" />
					<x-text-input class="block w-full"
						id="unit_serial"
						name="unit_serial"
						type="text"
						disabled
						:value="$staging->unit_serial"
						required
						autofocus />
					<x-input-error class="mt-2"
						:messages="$errors->get('unit_serial')" />
				</div>

				<div class="form-group col-md-6 p-2 pt-0">
					<x-input-label for="sla"
						:value="__('SLA')" />
					<input class="form-control block w-full"
						type="text"
						value="{{ $staging->sla }}"
						disabled />
					<x-input-error class="mt-2"
						:messages="$errors->get('sla')" />
				</div>

				@php
					$unitCategory = $staging->unit->category;
				@endphp

				@if ($unitCategory === App\Enums\UnitCategory::DESKTOP)
					<div class="form-group col-md-6 p-2">
						<x-input-label for="monitor_serial_number"
							:value="__('Monitor Serial Number')" />
						<x-text-input class="block w-full"
							id="monitor_serial_number"
							name="monitor_serial_number"
							type="text"
							:value="$staging->staging_monitor"
							disabled />
						<x-input-error class="mt-2"
							:messages="$errors->get('monitor_serial_number')" />
					</div>
				@endif

				<div class="form-group col-md-6 p-2">
					<x-input-label for="holder_name"
						:value="__('Holder Name')" />
					<input class="form-control block w-full"
						type="text"
						value="{!! $staging->holder_name !!}"
						disabled />
					<x-input-error class="mt-2"
						:messages="$errors->get('holder_name')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="company"
						:value="__('Company')" />
					<select class="form-select"
						id="company-select"
						name="company"
						disabled>
						@if ($staging->company)
							<option value="{{ $staging->company->id }}">{{ $staging->company->company_name }}</option>
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
						@if ($address)
							<option value="{{ $address->id }}">{{ $address->location }}</option>
						@else
							<option value=""></option>
						@endif
					</select>
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="service_code"
						:value="__('Service')" />
					<x-select-input class="block w-full"
						name="service_code"
						:model="$services"
						:selected="$staging->service->code ?? ''"
						disabled />

					<x-input-error class="mt-2"
						:messages="$errors->get('service_code')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="status"
						:value="__('Status')" />
					<x-select-input class="block w-full"
						name="status"
						:options="$statusOptions"
						:selected="$staging->status->value ?? ''" />

					<x-input-error class="mt-2"
						:messages="$errors->get('status')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="staging_start"
						:value="__('Start Date')" />
					<x-text-input class="block w-full"
						id="staging_start"
						name="staging_start"
						disabled
						:value="$staging->staging_start ? $staging->staging_start : ''"
						required
						autofocus />
					<x-input-error class="mt-2"
						:messages="$errors->get('staging_start')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="staging_finish"
						:value="__('Finish Date')" />
					<x-datetime-picker name="staging_finish"
						value="{{ $staging->staging_finish ? date('Y-m-d H:i', strtotime($staging->staging_finish)) : '' }}" />

					<x-input-error class="mt-2"
						:messages="$errors->get('staging_finish')" />
				</div>

				<div class="form-floating mt-2 px-1 py-3">
					<x-input-label for="staging_notes"
						:value="__('Staging Notes')" />
					<textarea class="form-control"
						id="editor"
						name="staging_notes"></textarea>
					<x-input-error class="mt-2"
						:messages="$errors->get('staging_notes')" />
				</div>

			</div>
		</div>
		<div class="card-footer">
			<div class="row">
				<div class="form-group d-flex justify-content-center">
					<x-success-button>
						{{ __('Update State') }}
					</x-success-button>
				</div>
			</div>
		</div>
	</form>
</div>

@push('js')
	<script>
		$('#stagingForm').on('submit', function(e) {
			e.preventDefault();
			let form = $(this);
			let url = form.attr('action');
			let method = form.attr('method');
			let data = form.serialize();
			let token = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				url: url,
				type: method,
				data: data,
				headers: {
					'X-CSRF-TOKEN': token
				}
				success: function(response) {
					$('#createModal').modal('hide');
					// Refresh the DataTable
					$('#stagings-table').DataTable().ajax.reload();
				},
				error: function(xhr) {
					// Handle errors
					console.log(xhr.responseText);
				}
			});
		});
	</script>
@endpush
