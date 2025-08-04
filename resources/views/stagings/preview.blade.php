<x-app-layout>
	<x-slot name="header">
		{{ __('Staging Import Preview') }}
	</x-slot>

	@php
		$hasMonitorSerials = isset($data[0]['Monitor Serial Number']);
		$hasHolderNames = isset($data[0]['Holder Name']);
	@endphp

	<div class="container-fluid">
		<x-alert type="error"
			color="danger" />

		<form action="{{ route('staging.import.process') }}"
			method="POST">
			@csrf

			<div class="card">
				<div class="card-header">
					<div class="card-title">
						Operational Unit :
						<h5><strong>{{ $operationalUnit->company_name . ' - ' . $operationalUnitAddress->location }}</strong></h5>
					</div>
				</div>

				<div class="card-body">
					<div class="row d-flex justify-content-around">
						<div class="col-md-5 mb-2">
							<div class="form-group">
								<x-input-label for="datetimepickerInput"
									:value="__('Start Date')" />

								<x-datetime-picker name="staging_start" />

								<x-input-error class="mt-2"
									:messages="$errors->get('staging_start')" />
							</div>
						</div>

						<div class="col-md-5 mb-2">
							<div class="form-group">
								<x-input-label for="batch_number"
									:value="__('Batch Number')" />

								<x-text-input name="batch_number"
									value="{{ $stagingNumber }}"
									readonly />

								<x-input-error class="mt-2"
									:messages="$errors->get('batch_number')" />
							</div>
						</div>
					</div>

					<div class="table-responsive border p-2">
						<table class="table-bordered table-striped table-nowrap table overflow-scroll"
							id="previewTable">
							<thead>
								<tr>
									<th style="max-width: 100px !important; min-width: 10px !important">Excel Row</th>
									<th>Serial Number</th>
									@if ($hasErrorMessage)
										<th class="text-danger">Error</th>
									@endif

									@if ($pairedMonitor)
										<th class="text-warning">Warning</th>
									@endif
									@if ($hasMonitorSerials)
										<th>Monitor Serial Number</th>
									@endif
									@if ($hasHolderNames)
										<th>Holder Name</th>
									@endif
									{{-- @if ($hasTermination)
										<th>SN Termination</th>
									@endif --}}
									<th>Service</th>
									<th>Company</th>
									<th>Location</th>
									<th>Request Category</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data as $row)
									<tr>
										<td class="text-center"
											style="max-width: 100px !important; min-width: 10px !important">{{ $row['row_number'] }}</td>
										<td>
											<x-text-input name="serials[{{ $row['Serial Number'] }}]"
												type="text"
												value="{{ $row['Serial Number'] }}"
												readonly />
										</td>
										@if ($hasErrorMessage)
											<td class="text-danger">
												{{ $row['message'] }}
											</td>
										@endif
										@if ($pairedMonitor)
											<td class="text-warning">
												{{ $row['paired_monitor'] }}
											</td>
										@endif
										@if ($hasMonitorSerials)
											<td>
												<x-text-input name="monitor_serials[{{ $row['Serial Number'] }}]"
													type="text"
													value="{{ $row['Monitor Serial Number'] }}"
													readonly />
											</td>
										@endif
										{{-- @if ($hasTermination)
											<td>
												<select class="form-select"
													name="terminations[{{ $row['Serial Number'] }}]"
													data-placeholder="Select Termination">

													@if (!empty($row['SN Termination']))
														<option value="{{ $row['termination_id'] }}">{{ $row['SN Termination'] }}</option>
													@else
														<option value=""></option>
													@endif
													@foreach ($terminations as $termination)
														<option value="{{ $termination->id }}">{{ $termination->unit_serial }}</option>
													@endforeach
												</select>
											</td>
										@endif --}}
										@if ($hasHolderNames)
											<td>
												<x-text-input name="holder_names[{{ $row['Serial Number'] }}]"
													type="text"
													value="{!! $row['Holder Name'] !!}"
													readonly />
											</td>
										@endif
										<td>
											<select class="form-select"
												name="services[{{ $row['Serial Number'] }}]"
												data-placeholder="Select Service"
												required>
												<option value=""></option>
												@foreach ($services as $service)
													<option value="{{ $service->code }}">{{ $service->label }}</option>
												@endforeach
											</select>
										</td>

										<td>
											<select class="form-select"
												id="companies_{{ $row['Serial Number'] }}"
												name="companies[{{ $row['Serial Number'] }}]"
												data-placeholder="Select Company"
												required>
												<option value=""></option>
												@foreach ($companies as $company)
													<option value="{{ $company->id }}">{{ $company->company_name }} ({{ $company->addresses->count() }})
													</option>
												@endforeach
											</select>
										</td>

										<td>
											<select class="form-select"
												id="addresses_{{ $row['Serial Number'] }}"
												name="addresses[{{ $row['Serial Number'] }}]"
												data-placeholder="Select Request Location"
												required>
												<option value=""></option>
											</select>
										</td>

										<td>
											<select class="form-select"
												name="categories[{{ $row['Serial Number'] }}]"
												data-placeholder="Select Request Category"
												required>
												<option value=""></option>
												@foreach ($categories as $key => $option)
													@if (is_array($option))
														<option value="{{ $option['value'] ?? $key }}">
															{{ $option['label'] }}
														</option>
													@else
														<option value="{{ $key }}">
															{{ $option }}
														</option>
													@endif
												@endforeach
											</select>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						@if ($hasErrorMessage)
							<div class="alert alert-danger col-md-11 alert-dismissible fade show mt-3">
								<strong>Error!</strong>
								<ul>
									@foreach ($data as $row)
										@if (!empty($row['message']))
											<li>Row {{ $row['row_number'] }}: {{ $row['message'] }}</li>
										@endif
									@endforeach
								</ul>
								<button class="btn-close"
									data-bs-dismiss="alert"
									type="button"
									aria-label="Close"></button>
							</div>
							<div class="col-md-1 d-flex justify-content-center align-items-center">
								<a class="btn btn-primary mt-3"
									href="{{ route('staging') }}">Back</a>
							</div>
						@else
							<div class="col-md-6 d-flex justify-content-end">
								<a class="btn btn-secondary mt-3"
									href="{{ route('staging') }}">Back</a>
							</div>
							<div class="col-md-6">
								<button class="btn btn-success confirm-import mt-3"
									type="submit">Process Stagings</button>
							</div>
						@endif
					</div>
				</div>
			</div>
		</form>
	</div>

	<style>
		#previewTable {
			width: 100% !important;
		}

		#previewTable th,
		#previewTable td {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			/* max-width: 200px; */
			min-width: 150px;
		}
	</style>

	@push('js')
		<script type="text/javascript">
			function updateAddressDropdown(companyId, addressSelectId) {
				$.ajax({
					url: '/api/get-addresses/' + companyId, // Adjust this URL to your API endpoint
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						var addressSelect = $('#' + addressSelectId);
						addressSelect.empty().append('<option value=""></option>');


						$.each(data, function(key, value) {
							addressSelect.append($('<option>', {
								value: value.id,
								text: value.location
							}));
						});

						addressSelect.trigger('change'); // Refresh Select2
					},
					error: function(xhr, status, error) {
						console.error("Error fetching addresses: " + error);
					}
				});
			}

			// Attach change event to all company dropdowns
			$('select[id^="companies_"]').each(function() {
				var companySelectId = $(this).attr('id');
				var serialNumber = companySelectId.split('_')[1];
				var addressSelectId = 'addresses_' + serialNumber;

				$(this).on('change', function() {
					var selectedCompanyId = $(this).val();
					if (selectedCompanyId) {
						updateAddressDropdown(selectedCompanyId, addressSelectId);
					} else {
						$('#' + addressSelectId).empty().append('<option value=""></option>').trigger('change');
					}

				});
			});
		</script>

		<script type="text/javascript">
			$(function() {

				$('.confirm-import').on('click', function(event) {
					let form = $(this).closest("form");
					let url = form.attr('action');
					// let method = form.attr('method');
					let data = form.serialize();
					let token = $('meta[name="csrf-token"]').attr('content');
					event.preventDefault();

					if (!validateForm(form)) {
						return; // Jika validasi gagal, hentikan proses
					}

					swal.fire({
							title: "Do you want to import data?",
							showDenyButton: true,
							showCancelButton: true,
							confirmButtonText: "Import",
							denyButtonText: `Don't import`,
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
											title: "Data has been imported",
											showConfirmButton: false,
											timer: 1500
										});

										// Redirect setelah sukses
										setTimeout(function() {
											window.location.href =
												'/staging'; // Ganti dengan route tujuan
										}, 1500);
									},
									error: function(xhr) {
										console.log(xhr.responseJSON.message);
										let errors = xhr.responseJSON.errors;
										let errorMessages = [];

										if (errors) {
											Object.keys(errors).forEach(function(key) {
												errorMessages.push(errors[key]);
											});

											Swal.fire({
												icon: 'error',
												title: 'Oops...',
												text: 'Something went wrong!',
												html: errorMessages.join('<br>'),
											});

										}

									}
								});
							} else if (result.isDenied) {
								Swal.fire("Changes are not imported", "", "info");
							}
						});
				});

				function validateForm(form) {
					let isValid = true;
					let errorMessages = [];

					// Validasi untuk input biasa dan select
					form.find('input[required], select[required]').each(function() {
						let value = $(this).val();
						if (value === null || value.trim() === '') {
							isValid = false;
							$(this).addClass('is-invalid');
						} else {
							$(this).removeClass('is-invalid');
						}
					});

					// Validasi khusus untuk array inputs (companies, services, categories, dll)
					let arrayInputs = ['companies', 'services', 'categories', 'addresses'];
					arrayInputs.forEach(function(inputName) {
						let inputs = form.find(`select[name^="${inputName}"], input[name^="${inputName}"]`);
						if (inputs.length === 0 || inputs.filter(function() {
								return $(this).val() !== '';
							}).length === 0) {
							errorMessages.push(`Please select ${inputName} for each rows!`);
							isValid = false;
							inputs.addClass('is-invalid');
						} else {
							inputs.removeClass('is-invalid');
						}
					});

					let startDate = form.find('input[name="staging_start"]');
					if (startDate.val() === '') {
						errorMessages.push('Please input start date!');
						isValid = false;
						startDate.addClass('is-invalid');
					} else {
						startDate.removeClass('is-invalid');
					}

					if (!isValid) {
						Swal.fire({
							icon: 'error',
							title: 'Validation Error',
							html: errorMessages.join('<br>'),
							confirmButtonText: 'OK'
						});
					}

					return isValid;
				}

				$('#previewTable').DataTable({
					processing: true,
					scrollX: true,
					paging: true,
					responsive: true,
					searching: false,
					buttons: [],
					pageLength: 50,
					lengthMenu: [
						[10, 25, 50, 100, -1],
						[10, 25, 50, 100, 'All'],
					],
					dom: '<"top"Bl><"clear">rt<"bottom"ip>',
					ordering: false
				})
			})
			$('.form-select').each(function() {
				$(this).select2({
					theme: 'bootstrap-5',
					placeholder: $(this).data('placeholder'),
					debug: true,
					allowClear: true,
					language: 'en',
					value: $(this).data('default-value'),
				});
			});
		</script>
	@endpush
</x-app-layout>
