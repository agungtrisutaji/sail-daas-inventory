<x-app-layout>

	<x-slot name="header">
		{{ __('Deployment') }}
	</x-slot>

	{{-- <x-slot name="breadcrumb">
        {{ Breadcrumbs::render('deployment.show', $deployment) }}
    </x-slot> --}}
	<div class="container">
		<div class="card">
			<x-create-form-v action="{{ route('deployment.update', $deployment) }}"
				:formId="'deploymentForm'"
				method="PUT"
				noSubmitButton>

				<div class="card-header">
					<div class="card-title">
						<h5><strong>{{ $deployment->unit_serial }}</strong></h5>
					</div>
					<div class="card-tools">
						<a class="btn btn-secondary mx-2"
							href="{{ route('deployment') }}">
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
							<x-input-label for="bast_number"
								:value="__('BAST Number')" />
							<x-text-input class="mt-1 block w-full"
								id="bast_number"
								name="bast_number"
								type="text"
								:value="$deployment->bast_number" />
							<x-input-error class="mt-2"
								:messages="$errors->get('bast_number')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="ritm_number"
								:value="__('RITM Number')" />
							<x-text-input class="mt-1 block w-full"
								id="ritm_number"
								name="ritm_number"
								type="text"
								:value="$deployment->ritm_number" />
							<x-input-error class="mt-2"
								:messages="$errors->get('ritm_number')" />
						</div>

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
							<x-input-label for="company"
								:value="__('Company')" />
							<select class="form-select"
								id="company-select"
								name="company">
								@if ($deployment->company)
									<option value="{{ $deployment->company->id }}">{{ $deployment->company->company_name }}</option>
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
								data-placeholder="Select Request Location">
								@if ($address)
									<option value="{{ $address->id }}">{{ $address->location }}</option>
								@else
									<option value=""></option>
								@endif
							</select>
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="status"
								:value="__('Status')" />
							<x-select-input class="block w-full"
								name="status"
								:options="$statusOptions"
								:selected="$deployment->status->value ?? ''" />

							<x-input-error class="mt-2"
								:messages="$errors->get('status')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="approved_by"
								:value="__('Approved By')" />
							<x-text-input class="mt-1 block w-full"
								id="approved_by"
								name="approved_by"
								type="text"
								:value="$deployment->approved_by" />
							<x-input-error class="mt-2"
								:messages="$errors->get('approved_by')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="bast_date"
								:value="__('BAST Date')" />
							<x-datetime-picker name="bast_date"
								value="{{ $deployment->bast_date ? date('Y-m-d H:i', strtotime($deployment->bast_date)) : '' }}" />
							<x-input-error class="mt-2"
								:messages="$errors->get('bast_date')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="bast_sign_date"
								:value="__('BAST Sign Date')" />
							<x-datetime-picker name="bast_sign_date"
								value="{{ $deployment->bast_sign_date ? date('Y-m-d H:i', strtotime($deployment->bast_sign_date)) : '' }}" />
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
								placeholder="Deployment Notes"></textarea>
							<x-input-error class="mt-2"
								:messages="$errors->get('deployment_note')" />
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
								{{ __('Update Deployment') }}
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
						action="{{ route('deployment.destroy', $deployment) }}"
						method="POST">
						@csrf
						@method('DELETE')
						<button class="btn btn-outline-danger confirm-delete"
							for="deleteForm">
							{{ __('Delete Deployment') }}
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
								success: function(response) {
									Swal.fire({
										position: "top-center",
										icon: "success",
										title: "Data has been updated",
										showConfirmButton: false,
										timer: 1500
									});

									setTimeout(function() {
										window.location.reload();
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
								type: 'POST', // Pastikan POST digunakan untuk metode penghapusan jika form menggunakan POST
								data: {
									_method: 'DELETE', // Menggunakan metode DELETE
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
											'/deployment'; // Ganti dengan route tujuan
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
		</script>
	@endpush

</x-app-layout>
