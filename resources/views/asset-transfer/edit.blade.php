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

	<div class="card-header">
		<h3 class="card-title">
			{{ __($assetTransfer->transfer_number) }}
		</h3>

		<div class="card-tools">
			<a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
				href="{{ route('asset-transfer.show', $assetTransfer) }}">
				{{ __('Edit') }}
			</a>
		</div>
	</div>

	<form id="assetTransferForm"
		action="{{ route('asset-transfer.update-state', $assetTransfer) }}"
		method="POST">
		@csrf
		@method('PUT')
		<div class="card-body pt-1">
			<x-alert type="success" />
			<x-alert type="error"
				color="danger" />

			<div class="row">
				<div class="form-group col-md-6 p-2">
					<x-input-label for="jarvis_ticket"
						:value="__('Ticket Number')" />
					<x-input-text name="jarvis_ticket"
						value="{{ $assetTransfer->ritm_number }}"
						disabled
						:placeholder="__('Ticket Number')" />
					<x-input-error class="mt-2"
						:messages="$errors->get('jarvis_ticket')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="caller"
						:value="__('Caller')" />
					<x-input-text name="caller"
						value="{{ $assetTransfer->ticket->caller }}"
						disabled
						:placeholder="__('Caller')" />
					<x-input-error class="mt-2"
						:messages="$errors->get('caller')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="requestor"
						:value="__('Requestor')" />
					<x-input-text name="requestor"
						value="{{ $assetTransfer->ticket->requestor }}"
						disabled
						:placeholder="__('Requestor')" />
					<x-input-error class="mt-2"
						:messages="$errors->get('requestor')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="datetimepickerInput"
						:value="__('Start Date')" />

					<x-datetime-picker name="start_date"
						value="{{ $assetTransfer->start_date }}"
						disabled />
					<x-input-error class="mt-2"
						:messages="$errors->get('start_date')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="company_id"
						:value="__('Requestor Company')" />
					<select class="form-select select2"
						id="company-select"
						name="company_id"
						data-placeholder="Select Company"
						disabled>
						@if ($assetTransfer->ticket->company)
							<option value="{{ $assetTransfer->ticket->company->id }}">{{ $assetTransfer->ticket->company->company_name }}
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
					<x-input-label for="datetimepickerInput"
						:value="__('Finish Date')" />
					<x-datetime-picker name="finish_date"
						value="{{ $assetTransfer->finish_date ? date('Y-m-d H:i', strtotime($assetTransfer->finish_date)) : '' }}" />
					<x-input-error class="mt-2"
						:messages="$errors->get('finish_date')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label for="company_address"
						:value="__('Requestor Location')" />
					<select class="form-select select2"
						id="company-select"
						name="company_address"
						data-placeholder="Select Location"
						disabled>
						@if ($assetTransfer->ticket->address)
							<option value="{{ $assetTransfer->ticket->address->id }}">
								{{ $assetTransfer->ticket->address->location }}
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
						name="status"
						:label="__('Transfer Status')"
						:options="$statusOptions"
						:selected="$assetTransfer->status->value ?? ''" />
					<x-input-error class="mt-2"
						:messages="$errors->get('status')" />
				</div>

				<div class="form-group col-md-6 p-2">
					<x-input-label :value="__('Staging')" />
					<div class="btn-group w-100 mb-2"
						role="group"
						aria-label="staging options">
						<input class="btn-check"
							id="primary-outlined"
							name="is_restaging"
							type="radio"
							value=1
							autocomplete="off"
							disabled
							{{ $assetTransfer->is_restaging ? 'checked' : '' }}>
						<label class="btn btn-outline-primary"
							for="primary-outlined">Staging</label>

						<input class="btn-check"
							id="danger-outlined"
							name="is_restaging"
							type="radio"
							value=0
							disabled
							{{ !$assetTransfer->is_restaging ? 'checked' : '' }}
							autocomplete="off">
						<label class="btn btn-outline-danger"
							for="danger-outlined">No Staging</label>
					</div>
					<div class="mt-3"
						id="dynamicInput">
						<!-- Input fields will be dynamically inserted here -->
					</div>
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

				@if ($assetTransfer->is_restaging && $assetTransfer->is_restaging != 0)

					<div class="form-group col-md-6 p-2">
						<x-input-label for="opertional_id"
							:value="__('Opertional Unit')" />
						<select class="form-select select2"
							id="operational-select"
							name="opertional_id"
							data-placeholder="Select Operational Unit"
							disabled>
							@if ($assetTransfer->operationalUnit)
								<option value="{{ $assetTransfer->operationalUnit->id }}">{{ $assetTransfer->operationalUnit->company_name }}
								</option>
							@else
								<option value=""></option>
							@endif
							@foreach ($operationalUnits as $operationalUnit)
								<option value="{{ $operationalUnit->id }}">{{ $operationalUnit->company_name }}
									({{ $operationalUnit->addresses->count() }})
								</option>
							@endforeach
						</select>

						<x-input-error class="mt-2"
							:messages="$errors->get('opertional_id')" />
					</div>

					<div class="form-group col-md-6 p-2">
						<x-input-label for="operational_address"
							:value="__('Operational Location')" />
						<select class="form-select select2"
							id="operational-select"
							name="operational_address"
							data-placeholder="Select Location"
							disabled>
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
							<legend class="col-form-label col-sm-3 pt-0">QC Pass</legend>
							<div class="col-sm-9">
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
				@endif

				<div class="accordion accordion mb-3"
					id="accordionFlushExample">
					<div class="accordion-item">
						<div class="accordion-header">
							<button class="accordion-button collapsed"
								data-bs-toggle="collapse"
								data-bs-target="#collapseOne"
								type="button"
								aria-expanded="false"
								aria-controls="collapseOne"
								aria-describedby="accordionHelp">
								Holders Data
								<span class="form-text ps-2"
									id="accordionHelp">(Click to expand)</span>
							</button>
						</div>
						<div class="accordion-collapse collapse"
							id="collapseOne"
							data-bs-parent="#accordionFlushExample">
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
													value="{{ $assetTransfer->fromCompany?->company_name ?? '' }}"
													disabled />
												<x-input-error class="mt-2"
													:messages="$errors->get('from_company')" />
											</div>

											<div class="form-group">
												<x-input-label for="from_location"
													:value="__('Location')" />
												<x-input-text name="from_location"
													value="{{ $assetTransfer->fromLocation?->location ?? '' }}"
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
													:placeholder="__('To Holder')"
													disabled />
												<x-input-error class="mt-2"
													:messages="$errors->get('to_holder')" />
											</div>

											<div class="form-group">
												<x-input-label for="to_company_id"
													:value="__('To Company')" />
												<select class="form-select select2"
													id="to-company-select"
													name="to_company_id"
													data-placeholder="Select Company"
													disabled>
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
													data-placeholder="Select Location"
													disabled>
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

										<div class="card-footer">
											<small class="text-body-secondary">New holder data</small>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="accordion-item">
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
