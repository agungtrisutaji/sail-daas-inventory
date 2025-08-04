<x-app-layout>

	<x-slot name="header">
		{{ __('Asset Transfer Request') }}
	</x-slot>

	<div class="container">
		<div class="card">
			@if (session()->has('success'))
				<x-alert type="success">
					{{ session()->get('success') }}
				</x-alert>
			@endif
			<x-create-form-v class="needs-validation"
				noSubmitButton
				action="{{ route('asset-transfer.store') }}">
				<div class="card-header">
					<h3 class="text-center">New Asset Transfer</h3>
				</div>

				<div class="card-body">
					<div class="row px-3">

						<div class="form-group col-md-6 p-2">
							<x-input-label for="ritm_number"
								:value="__('Ticket Number')" />
							<x-input-text name="ritm_number"
								value="{{ old('ritm_number') }}"
								:placeholder="__('Ticket Number')" />
							<x-input-error class="mt-2"
								:messages="$errors->get('ritm_number')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label class="required"
								for="datetimepickerInput"
								:value="__('Start Date')" />

							<x-datetime-picker name="start_date"
								value="{{ old('start_date') }}" />
							<x-input-error class="mt-2"
								:messages="$errors->get('start_date')" />
						</div>

						<div class="form-group col-md-6 required p-2">
							<x-input-label for="caller"
								:value="__('Caller')"
								required />
							<x-input-text name="caller"
								value="{{ old('caller') }}"
								:placeholder="__('Caller')" />
							<x-input-error class="mt-2"
								:messages="$errors->get('caller')" />
						</div>

						<div class="form-group col-md-6 required p-2">
							<x-input-label for="requestor"
								:value="__('Requestor')"
								required />
							<x-input-text name="requestor"
								value="{{ old('requestor') }}"
								:placeholder="__('Requestor')" />
							<x-input-error class="mt-2"
								:messages="$errors->get('requestor')" />
						</div>

						<x-dynamic-select class="col-md-6 p-2"
							required
							:valueField="'id'"
							:label1="'Requestor Company'"
							:labelField="'company_name'"
							:labelCount="true"
							:countRelations="'addresses'"
							:parentName="'company_id'"
							:parentUrl="'api/fetch-companies'"
							:childUrl="'api/fetch-locations'"
							:children="[
							    [
							        'name' => 'company_address',
							        'label' => 'Requestor Location',
							        'childValue' => 'id',
							        'childLabel' => 'location',
							        'inputType' => 'select',
							    ],
							]" />

						<x-dynamic-select class="col-md-6 p-2"
							:valueField="'id'"
							:label1="'Operational Unit'"
							:labelField="'company_name'"
							:labelCount="true"
							:countRelations="'addresses'"
							:parentName="'operational_unit_id'"
							:parentUrl="'api/fetch-operational-units'"
							:childUrl="'api/fetch-operational-locations'"
							:children="[
							    [
							        'name' => 'operational_address',
							        'label' => 'Operational Location',
							        'childValue' => 'id',
							        'childLabel' => 'location',
							        'inputType' => 'select',
							        'required' => false,
							    ],
							]" />

						<div class="form-group col-md-6 p-2">

							<x-input-label :value="__('Staging')" />
							<div class="btn-group w-100 mb-2"
								role="group"
								aria-label="staging options">
								<input class="btn-check"
									id="primary-outlined"
									name="is_staging"
									type="radio"
									value=1
									autocomplete="off"
									checked>
								<label class="btn btn-outline-primary"
									for="primary-outlined">Staging</label>

								<input class="btn-check"
									id="danger-outlined"
									name="is_staging"
									type="radio"
									value=0
									autocomplete="off">
								<label class="btn btn-outline-danger"
									for="danger-outlined">No Staging</label>
							</div>
							<div class="mt-3"
								id="dynamicInput">
								<!-- Input fields will be dynamically inserted here -->
							</div>
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
													<x-dynamic-select class="serials"
														required
														:valueField="'serial'"
														:label1="'Serial Number'"
														:labelField="'serial'"
														:condition="'deployment'"
														:parentName="'serial'"
														:parentUrl="'api/fetch-unit-serials'"
														:childUrl="'api/fetch-units/deployment'"
														:children="[
														    [
														        'name' => 'from_holder_name',
														        'label' => 'From Holder Name',
														        'childValue' => 'holder_name',
														        'inputType' => 'text',
														        'required' => false,
														        'readonly' => true,
														    ],
														    [
														        'name' => 'from_company_name',
														        'label' => 'From Company Name',
														        'childValue' => 'company_name',
														        'inputType' => 'text',
														        'required' => false,
														        'disabled' => true,
														    ],
														    [
														        'name' => 'from_location',
														        'childLabel' => 'location',
														        'label' => 'From Location',
														        'childValue' => 'location',
														        'inputType' => 'text',
														        'required' => false,
														        'disabled' => true,
														    ],
														    [
														        'name' => 'unit_category',
														        'label' => 'Unit Category',
														        'childValue' => 'unit_category',
														        'inputType' => 'text',
														        'required' => false,
														        'disabled' => true,
														    ],
														    [
														        'name' => 'service',
														        'label' => 'Service',
														        'childValue' => 'service',
														        'inputType' => 'text',
														        'required' => false,
														        'disabled' => true,
														    ],
														    [
														        'name' => 'service_category',
														        'label' => 'Service Category',
														        'childValue' => 'service_category',
														        'inputType' => 'text',
														        'required' => false,
														        'disabled' => true,
														    ],
														    [
														        'name' => 'from_company_id',
														        'childValue' => 'company_id',
														        'inputType' => 'hidden',
														        'required' => false,
														    ],
														    [
														        'name' => 'from_address_id',
														        'childValue' => 'address_id',
														        'inputType' => 'hidden',
														        'required' => false,
														    ],
														]" />
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
														<x-input-text name="to_holder_name"
															value="{{ old('to_holder_name') }}"
															:placeholder="__('To Holder')" />
														<x-input-error class="mt-2"
															:messages="$errors->get('to_holder_name')" />
													</div>

													<x-dynamic-select required
														:valueField="'id'"
														:label1="'To Company'"
														:labelField="'company_name'"
														:labelCount="true"
														:countRelations="'addresses'"
														:parentName="'to_company_id'"
														:parentUrl="'api/fetch-companies'"
														:childUrl="'api/fetch-to-locations'"
														:children="[
														    [
														        'name' => 'to_company_address',
														        'label' => 'To Location',
														        'childValue' => 'id',
														        'childLabel' => 'location',
														        'inputType' => 'select',
														    ],
														]" />

												</div>

												<div class="form-group p-2">
													<div class="px-1 py-3">
														<x-input-label for="transfer_remark"
															:value="__('Transfer Remark')" />
														<textarea class="form-control"
														 id="editor"
														 name="transfer_remark"></textarea>
														<x-input-error class="mt-2"
															:messages="$errors->get('transfer_remark')" />
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
								{{ __('Create') }}
							</x-success-button>
						</div>
					</div>
				</div>

			</x-create-form-v>
		</div>
	</div>
</x-app-layout>
