<x-app-layout>

	<x-slot name="header">
		{{ __('Asset Transfer') }}
	</x-slot>

	{{-- <x-slot name="breadcrumb">
        {{ Breadcrumbs::render('staging.show', $assetTransfer) }}
    </x-slot> --}}
	<div class="container">
		<div class="card">
			<x-create-form-v action="{{ route('asset-transfer.update', $assetTransfer) }}"
				:formId="'assetTransferForm'"
				method="PUT"
				noSubmitButton>

				<div class="card-header">
					<div class="card-title">
						<h5><strong>{{ $assetTransfer->transfer_number }}</strong></h5>
					</div>
					<div class="card-tools">
						<a class="btn btn-secondary mx-2"
							href="{{ route('asset-transfer') }}">
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
							<x-input-label for="jarvis_ticket"
								:value="__('Ticket Number')" />
							<x-input-text name="jarvis_ticket"
								value="{{ $assetTransfer->ritm_number }}"
								:placeholder="__('Ticket Number')" />
							<x-input-error class="mt-2"
								:messages="$errors->get('jarvis_ticket')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="datetimepickerInput"
								:value="__('Start Date')" />

							<x-datetime-picker name="start_date"
								value="{{ $assetTransfer->start_date ? date('Y-m-d H:i', strtotime($assetTransfer->start_date)) : '' }}"
								disabled />
							<x-input-error class="mt-2"
								:messages="$errors->get('start_date"')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-select-input class="select2 block w-full"
								name="status"
								:label="__('Transfer Status')"
								:options="$statusOptions"
								:selected="$assetTransfer->status->value ?? ''" />
							<x-input-error class="mt-2"
								:messages="$errors->get('status')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="datetimepickerInput"
								:value="__('Finish Date')" />
							<x-datetime-picker name="finish_date"
								value="{{ $assetTransfer->finish_date ? date('Y-m-d H:i', strtotime($assetTransfer->finish_date)) : '' }}" />
							<x-input-error class="mt-2"
								:messages="$errors->get('finish_date')" />
						</div>

						<div class="form-group col-md-6 required p-2">
							<x-input-label for="caller"
								:value="__('Caller')"
								required />
							<x-input-text name="caller"
								value="{{ $assetTransfer->ticket->caller }}"
								:placeholder="__('Caller')" />
							<x-input-error class="mt-2"
								:messages="$errors->get('caller')" />
						</div>

						<div class="form-group col-md-6 required p-2">
							<x-input-label for="requestor"
								:value="__('Requestor')"
								required />
							<x-input-text name="requestor"
								value="{{ $assetTransfer->ticket->requestor }}"
								:placeholder="__('Requestor')" />
							<x-input-error class="mt-2"
								:messages="$errors->get('requestor')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label class="required"
								for="company"
								:value="__('Requestor Company')" />
							<select class="form-select select2"
								id="company-select"
								name="company_id"
								data-placeholder="Select Requestor Company"
								required>
								@if ($assetTransfer->ticket->company)
									<option value="{{ $assetTransfer->ticket->company->id }}">
										{{ $assetTransfer->ticket->company->company_name }}
									</option>
								@else
									<option value=""></option>
								@endif
								@foreach ($companies as $company)
									<option value="{{ $company->id }}">{{ $company->company_name }}
										({{ $company->addresses->count() }})
									</option>
								@endforeach
							</select>

							<x-input-error class="mt-2"
								:messages="$errors->get('company_id')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label class="required"
								for="address"
								:value="__('Requestor Location')" />
							<select class="form-select select2"
								id="address-select"
								name="company_address"
								data-placeholder="Select Request Location"
								required>
								@if ($assetTransfer->ticket->address)
									<option value="{{ $assetTransfer->ticket->address->id }}">{{ $assetTransfer->ticket->address->location }}
									</option>
								@else
									<option value=""></option>
								@endif
							</select>

							<x-input-error class="mt-2"
								:messages="$errors->get('company_address')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-select-input class="select2 block w-full"
								name="document_availability"
								:label="'Document Availability'"
								:options="$documentOptions"
								:selected="$assetTransfer->document_availability ?? ''" />
							<x-input-error class="mt-2"
								:messages="$errors->get('document_availability')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label :value="__('Staging')" />
							<div class="btn-group w-100"
								role="group"
								aria-label="staging options">
								<input class="btn-check"
									id="primary-outlined"
									name="is_restaging"
									type="radio"
									value=1
									autocomplete="off"
									{{ $assetTransfer->is_restaging ? 'checked' : '' }}>
								<label class="btn btn-outline-primary"
									for="primary-outlined">Staging</label>

								<input class="btn-check"
									id="danger-outlined"
									name="is_restaging"
									type="radio"
									value=0
									autocomplete="off"
									{{ !$assetTransfer->is_restaging ? 'checked' : '' }}>
								<label class="btn btn-outline-danger"
									for="danger-outlined">No Staging</label>
							</div>
							<div id="dynamicInput">
								<!-- Input fields will be dynamically inserted here -->
							</div>
						</div>

						<div class="form-group col-md-6">
							<x-input-label for="operational_id"
								:value="__('Operational Unit')" />
							<select class="form-select select2"
								id="operational-select"
								name="operational_id"
								data-placeholder="Select Operational Unit">
								@if ($assetTransfer->operationalUnit)
									<option value="{{ $assetTransfer->operationalUnit->id }}">
										{{ $assetTransfer->operationalUnit->company_name }}
									</option>
								@else
									<option value=""></option>
								@endif
								@foreach ($companies as $company)
									<option value="{{ $company->id }}">{{ $company->company_name }}
										({{ $company->addresses->count() }})
									</option>
								@endforeach
							</select>

							<x-input-error class="mt-2"
								:messages="$errors->get('operational_id')" />
						</div>

						<div class="form-group col-md-6">
							<x-input-label for="operational_address"
								:value="__('Operational Location')" />
							<select class="form-select select2"
								id="operational-address-select"
								name="operational_address"
								data-placeholder="Select Operational Location">
								@if ($assetTransfer->operationalLocation)
									<option value="{{ $assetTransfer->operationalLocation->id }}">
										{{ $assetTransfer->operationalLocation->location }}
									</option>
								@else
									<option value=""></option>
								@endif
							</select>

							<x-input-error class="mt-2"
								:messages="$errors->get('operational_address')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="qc_by"
								:value="__('QC By')" />
							<x-input-text name="qc_by"
								value="{{ $assetTransfer->qc_by }}"
								:placeholder="__('QC By')" />
							<x-input-error class="mt-2"
								:messages="$errors->get('qc_by')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label for="datetimepickerInput"
								:value="__('QC Date')" />
							<x-datetime-picker name="qc_date"
								value="{{ $assetTransfer->qc_date ? date('Y-m-d H:i', strtotime($assetTransfer->qc_date)) : '' }}" />
							<x-input-error class="mt-2"
								:messages="$errors->get('qc_date')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<fieldset class="row py-2">
								<legend class="col-form-label col-sm-2 pt-0">QC Pass</legend>
								<div class="col-sm-10">
									<div class="form-check">
										<input class="form-check-input"
											id="gridRadios1"
											name="qc_pass"
											type="radio"
											value=1
											{{ $assetTransfer->qc_pass !== null && $assetTransfer->qc_pass ? 'checked' : '' }}>
										<label class="form-check-label"
											for="qc_pass1">
											Pass
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input"
											id="qc_pass2"
											name="qc_pass"
											type="radio"
											value=0
											{{ $assetTransfer->qc_pass !== null && !$assetTransfer->qc_pass ? 'checked' : '' }}>
										<label class="form-check-label"
											for="qc_pass2">
											Not Pass
										</label>
									</div>
								</div>
							</fieldset>

							<x-input-error class="mt-2"
								:messages="$errors->get('requestor')" />
						</div>

						<div class="accordion mt-3 p-2"
							id="accordionHolders">
							<div class="accordion-item">
								<h2 class="accordion-header"> <button class="accordion-button"
										data-bs-toggle="collapse"
										data-bs-target="#collapseOne"
										type="button"
										aria-expanded="true"
										aria-controls="collapseOne">
										Holders Data
									</button> </h2>
								<div class="accordion-collapse show collapse"
									id="collapseOne"
									data-bs-parent="#accordionHolders"
									style="">
									<div class="accordion-body p-0">
										<div class="card-group">
											<div class="card holders">
												<div class="card-header">
													<h3 class="card-title">From</h3>
												</div>
												<div class="card-body">

													<div class="form-group">
														<x-input-label for="unit_serial"
															:value="__('Serial Number')" />
														<x-input-text name="unit_serial"
															value="{{ $assetTransfer->unit_serial }}"
															disabled
															:placeholder="__('Ticket Number')" />
														<x-input-error class="mt-2"
															:messages="$errors->get('unit_serial')" />
													</div>

													<div class="form-group">
														<x-input-label for="from_holder"
															:value="__('Holder Name')" />
														<x-input-text name="from_holder"
															value="{{ $assetTransfer->from_holder }}"
															disabled />
														<x-input-error class="mt-2"
															:messages="$errors->get('from_holder')" />
													</div>

													<div class="form-group">
														<x-input-label for="from_company"
															:value="__('Company Name')" />
														<x-input-text name="from_company"
															value="{{ $assetTransfer->fromCompany->company_name }}"
															disabled />
														<x-input-error class="mt-2"
															:messages="$errors->get('from_company')" />
													</div>

													<div class="form-group">
														<x-input-label for="from_location"
															:value="__('Location')" />
														<x-input-text name="from_location"
															value="{{ $assetTransfer->fromLocation->location }}"
															disabled />
														<x-input-error class="mt-2"
															:messages="$errors->get('from_location')" />
													</div>

													<div class="form-group">
														<x-input-label for="unit_category"
															:value="__('Category')" />
														<x-input-text name="unit_category"
															value="{{ $assetTransfer->unit->category }}"
															disabled />
														<x-input-error class="mt-2"
															:messages="$errors->get('unit_category')" />
													</div>

													<div class="form-group">
														<x-input-label for="service"
															:value="__('Service')" />
														<x-input-text name="service"
															value="{{ $service }}"
															disabled />
														<x-input-error class="mt-2"
															:messages="$errors->get('service')" />
													</div>

													<div class="form-group">
														<x-input-label for="service_category"
															:value="__('Service Category')" />
														<x-input-text name="service_category"
															value="{{ $serviceCategory }}"
															disabled />
														<x-input-error class="mt-2"
															:messages="$errors->get('service_category')" />
													</div>

												</div>

												<div class="card-footer">
													<small class="text-body-secondary">Old holder data</small>
												</div>
											</div>
											<div class="card holders">
												<div class="card-header">
													<h3 class="card-title">To</h3>
												</div>

												<div class="card-body">

													<div class="form-group">
														<x-input-label for="to_holder"
															:value="__('To Holder')" />
														<x-input-text name="to_holder"
															value="{{ $assetTransfer->to_holder }}"
															:placeholder="__('To Holder')" />
														<x-input-error class="mt-2"
															:messages="$errors->get('to_holder')" />
													</div>

													<div class="form-group">
														<x-input-label for="to_company_id"
															:value="__('To Company')" />
														<select class="form-select select2"
															id="to-company-select"
															name="to_company_id"
															data-placeholder="Select Company">
															@if ($assetTransfer->toCompany)
																<option value="{{ $assetTransfer->toCompany->id }}">{{ $assetTransfer->toCompany->company_name }}
																</option>
															@else
																<option value=""></option>
															@endif
															@foreach ($companies as $company)
																<option value="{{ $company->id }}">{{ $company->company_name }}
																	({{ $company->addresses->count() }})
																</option>
															@endforeach
														</select>

														<x-input-error class="mt-2"
															:messages="$errors->get('to_company_id')" />
													</div>

													<div class="form-group">
														<x-input-label for="to_company_address"
															:value="__('Location')" />
														<select class="form-select select2"
															id="to-address-select"
															name="to_company_address"
															data-placeholder="Select Location">
															@if ($assetTransfer->toLocation)
																<option value="{{ $assetTransfer->toLocation->id }}">{{ $assetTransfer->toLocation->location }}
																</option>
															@else
																<option value=""></option>
															@endif
														</select>

														<x-input-error class="mt-2"
															:messages="$errors->get('to_company_address')" />
													</div>

												</div>

												<div class="form-group p-2">
													<x-input-label for="transfer_remark"
														:value="__('Transfer Remark')" />
													<textarea class="form-control"
													 id="editor"
													 name="transfer_remark"
													 value="{{ $assetTransfer->transfer_remark }}">{{ $assetTransfer->transfer_remark }}</textarea>
													<x-input-error class="mt-2"
														:messages="$errors->get('transfer_remark')" />
												</div>

												<div class="card-footer">
													<small class="text-body-secondary">New holder data</small>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
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
							@if ($errors->any())
								<div class="alert alert-danger alert-dismissible fade show">
									<ul>
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif
							<x-success-button>
								{{ __('Update') }}
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
						action="{{ route('asset-transfer.destroy', $assetTransfer) }}"
						method="POST">
						@csrf
						@method('DELETE')
						<button class="btn btn-outline-danger confirm-delete"
							for="deleteForm">
							{{ __('Delete') }}
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
											"{{ route('asset-transfer') }}";
									}, 1500);
								},
								error: function(xhr) {
									let errors = xhr.responseJSON.errors;
									let errorMessages = [];


									Object.keys(errors).forEach(function(key) {
										errorMessages.push(errors[key]);
									});


									Swal.fire({
										icon: 'error',
										title: 'Oops...',
										html: errorMessages.join('<br>'),
										confirmButtonText: 'OK',

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
											"{{ route('asset-transfer') }}";
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
					delay: 250,
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
				let operationalSelect = $('#operational-select');
				let toCompanySelect = $('#to-company-select');
				let addressSelect = $('#address-select');
				let operationalAddressSelect = $('#operational-address-select');
				let toAddressSelect = $('#to-address-select');

				if (!companySelect) {
					return;
				}

				if (!operationalSelect) {
					return;
				}

				if (!toCompanySelect) {
					return;
				}

				companySelect.select2({
					theme: 'bootstrap-5',
					allowClear: true
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

				operationalSelect.select2({
					theme: 'bootstrap-5',
					allowClear: true
				}).on('change', (event) => {
					let companyId = event.target.value;
					let addressSelectId = $(operationalAddressSelect).attr('id');
					if (companyId) {
						getLocationsList(companyId, addressSelectId);
					} else {
						operationalAddressSelect
							.empty()
							.append('<option value="">Select an address</option>')
							.trigger('change');
					}
				});

				toCompanySelect.select2({
					theme: 'bootstrap-5',
					allowClear: true
				}).on('change', (event) => {
					let companyId = event.target.value;
					let addressSelectId = $(toAddressSelect).attr('id');
					if (companyId) {
						getLocationsList(companyId, addressSelectId);
					} else {
						toAddressSelect
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

			$('#operational-address-select').select2({
				theme: 'bootstrap-5',
				allowClear: true
			});

			$('#to-address-select').select2({
				theme: 'bootstrap-5',
				allowClear: true
			});
		</script>
	@endpush

</x-app-layout>
