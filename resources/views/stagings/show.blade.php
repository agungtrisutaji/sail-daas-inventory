<x-app-layout>

	<x-slot name="header">
		{{ __('Staging') }}
	</x-slot>

	{{-- <x-slot name="breadcrumb">
        {{ Breadcrumbs::render('staging.show', $staging) }}
    </x-slot> --}}
	<div class="container">
		<div class="card">
			<x-create-form-v action="{{ route('staging.update', $staging) }}"
				:formId="'stagingForm'"
				method="PUT"
				noSubmitButton>

				<div class="card-header">
					<div class="card-title">
						<h5><strong>{{ $staging->unit_serial }}</strong></h5>
					</div>
					<div class="card-tools">
						<a class="btn btn-secondary mx-2"
							href="{{ route('staging') }}">
							{{ __('Back') }}
						</a>
					</div>
				</div>

				<div class="card-body">
					<x-alert type="success" />
					<x-alert type="error"
						color="danger" />
					<div class="row">
						<div class="form-group col-md-6 p-2">
							<x-input-label for="unit_serial"
								:value="__('Serial Number')" />
							<x-text-input class="block w-full"
								id="unit_serial"
								name="unit_serial"
								type="text"
								disabled
								:value="$staging->unit_serial"
								required />
							<x-input-error class="mt-2"
								:messages="$errors->get('unit_serial')" />
						</div>

						<div class="form-group col-md-6 p-2">
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
								<x-input-label for="staging_monitor"
									:value="__('Monitor Serial Number')" />
								<x-text-input class="block w-full"
									id="staging_monitor"
									name="staging_monitor"
									type="text"
									:value="$staging->staging_monitor"
									required />
								<x-input-error class="mt-2"
									:messages="$errors->get('staging_monitor')" />
							</div>
						@endif

						<div class="form-group col-md-6 p-2">
							<x-input-label for="holder_name"
								:value="__('Holder Name')" />
							<x-input-text name="holder_name"
								value="{!! $staging->holder_name !!}" />
							<x-input-error class="mt-2"
								:messages="$errors->get('holder_name')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="company"
								:value="__('Company')" />
							<select class="form-select select2"
								id="company-select"
								name="company_id">
								@if ($staging->company)
									<option value="{{ $staging->company->id }}">{{ $staging->company->company_name }}</option>
								@else
									<option value=""></option>
								@endif
								@foreach ($companies as $company)
									<option value="{{ $company->id }}">{{ $company->company_name }} ({{ $company->addresses->count() }})</option>
								@endforeach
							</select>

							<x-input-error class="mt-2"
								:messages="$errors->get('company_id')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="address"
								:value="__('Location')" />
							<select class="form-select select2"
								id="address-select"
								name="company_address"
								data-placeholder="Select Request Location">
								@if ($address)
									<option value="{{ $address->id }}">{{ $address->location }}</option>
								@else
									<option value=""></option>
								@endif
							</select>

							<x-input-error class="mt-2"
								:messages="$errors->get('company_address')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="service_code"
								:value="__('Service')" />
							<x-select-input class="select2 block w-full"
								name="service_code"
								:model="$services"
								:selected="$staging->service->code ?? ''" />

							<x-input-error class="mt-2"
								:messages="$errors->get('service_code')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="request_category"
								:value="__('Request Category')" />
							<x-select-input class="select2 block w-full"
								name="request_category"
								:options="$categories"
								:selected="$staging->request_category->value ?? ''" />

							<x-input-error class="mt-2"
								:messages="$errors->get('request_category')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="staging_start"
								:value="__('Start Date')" />
							<x-text-input class="block w-full"
								id="staging_start"
								name="staging_start"
								:value="$staging->staging_start ? date('Y-m-d H:i', strtotime($staging->staging_start)) : ''"
								disabled />
							<x-input-error class="mt-2"
								:messages="$errors->get('staging_start')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="status"
								:value="__('Status')" />
							<x-select-input class="select2 block w-full"
								name="status"
								:options="$statusOptions"
								:selected="$staging->status->value ?? ''" />

							<x-input-error class="mt-2"
								:messages="$errors->get('status')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="datetimepickerInput"
								:value="__('Finish Date')" />

							<x-datetime-picker name="staging_finish"
								value="{{ $staging->staging_finish ? date('Y-m-d H:i', strtotime($staging->staging_finish)) : '' }}" />
							<x-input-error class="mt-2"
								:messages="$errors->get('staging_finish')" />
						</div>

						<div class="form-floating py-3 ps-1">
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
						<div class="col-md-10">
							@if ($errors->any())
								<div class="alert alert-danger alert-dismissible fade show">
									<strong>Warning!</strong>
									<ul>
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
									<button class="btn-close"
										data-bs-dismiss="alert"
										type="button"
										aria-label="Close"></button>
								</div>
							@endif
						</div>
						<div class="d-flex col-md-2 justify-content-end confirm-submit">
							<x-success-button>
								{{ __('Update Staging') }}
							</x-success-button>
						</div>
					</div>
				</div>
			</x-create-form-v>
		</div>

		<div class="card mt-3 p-2">
			<div class="row">
				<div class="col-sm-10">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Placeat, assumenda? Magni sequi,
					consectetur, maiores architecto aliquid deleniti accusantium ipsam impedit sit, dicta dolor ipsum! Enim alias
					consequuntur dolores, quisquam earum sed corrupti soluta ex molestiae excepturi qui dolor quasi eligendi eum esse,
					laudantium eaque, sint sit ipsum corporis perspiciatis impedit?</div>
				<div class="col-sm-2 d-flex">

					<form class="delete-form d-inline w-100 h-100 d-flex justify-content-center align-items-center p-1"
						id="deleteForm"
						action="{{ route('staging.destroy', $staging) }}"
						method="POST">
						@csrf
						@method('DELETE')
						<button class="btn btn-outline-danger confirm-delete"
							for="deleteForm">
							{{ __('Delete Staging') }}
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	@push('js')
		<script>
			$('.confirm-submit').on('click', function(event) {
				var form = $(this).closest("form");
				var url = form.attr('action'); // Mengambil URL dari atribut 'action' form
				let data = form.serialize();
				var token = $('meta[name="csrf-token"]').attr('content'); // Mengambil token CSRF Laravel
				event.preventDefault();

				swal.fire({
						title: "Are you sure to submit update?",
						text: "You won't be able to revert this!",
						icon: "question",
						showCancelButton: true,
						confirmButtonColor: "#3085d6",
						cancelButtonColor: "#d33",
						confirmButtonText: "Yes, submit!"
					})
					.then((result) => {
						if (result.isConfirmed) {
							$.ajax({
								url: url,
								type: 'POST',
								data: data,
								headers: {
									'X-CSRF-TOKEN': token
								},
								success: function(response) {
									Swal.fire({
										position: "top-center",
										icon: "success",
										title: "Data has been updated",
										showConfirmButton: false,
										timer: 1500
									});

									setTimeout(function() {
										window.location.href =
											"{{ route('staging') }}";
									}, 1500);
								},
								error: function(xhr) {
									Swal.fire({
										icon: 'error',
										title: 'Oops...',
										text: 'Something went wrong!',
									});
								}
							});
						}
					});
			});

			$('.confirm-delete').on('click', function(event) {
				var form = $(this).closest("form");
				var url = form.attr('action'); // Mengambil URL dari atribut 'action' form
				var token = $('meta[name="csrf-token"]').attr('content'); // Mengambil token CSRF Laravel
				event.preventDefault();

				swal.fire({
						title: "Are you sure?",
						text: "You won't be able to revert this!",
						icon: "warning",
						showCancelButton: true,
						confirmButtonColor: "#3085d6",
						cancelButtonColor: "#d33",
						confirmButtonText: "Yes, delete it!"
					})
					.then((result) => {
						if (result.isConfirmed) {
							$.ajax({
								url: url,
								type: 'POST',
								data: {
									_method: 'DELETE',
									_token: token
								},
								success: function(response) {
									Swal.fire({
										position: "top-center",
										icon: "success",
										title: "Data has been deleted",
										showConfirmButton: false,
										timer: 1500
									});

									// Redirect setelah sukses
									setTimeout(function() {
										window.location.href =
											"{{ route('staging') }}";
									}, 1500);
								},
								error: function(xhr) {
									Swal.fire({
										icon: 'error',
										title: 'Oops...',
										text: 'Something went wrong!',
									});
								}
							});
						}
					});
			});


			function getLocationsList(companyId, addressSelectId) {
				$.ajax({
					url: '/api/get-addresses/' + companyId,
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						var addressSelect = $('#' + addressSelectId);
						addressSelect.empty().append('<option value=""></option>');

						$.each(data, function(key, value) {
							addressSelect.append(
								$('<option>', {
									value: value.id,
									text: value.location,
								})
							);
						});

						addressSelect.trigger('change');
					},
					error: function(xhr, status, error) {
						console.error('Error fetching addresses: ' + error);
					},
				});
			}

			function companyLocation() {
				let companySelect = $('#company-select');
				let addressSelect = $('#address-select');
				if (!companySelect) {
					return;
				}

				companySelect.select2({
					theme: 'bootstrap-5',
				}).on('change', (event) => {
					let companyId = event.target.value;
					let addressSelectId = $(addressSelect).attr('id');
					if (companyId) {
						getLocationsList(companyId, addressSelectId);
					} else {
						addressSelect
							.empty()
							.append('<option value="">Select an address</option>')
							.trigger('change');
					}
				});
			}

			companyLocation();

			$('#address-select').select2({
				theme: 'bootstrap-5',
				allowClear: true
			});
		</script>
	@endpush

</x-app-layout>
