<x-app-layout>

	<x-slot name="header">
		{{ __('Termination Request') }}
	</x-slot>

	<div class="container">
		<div class="card">
			@if (session()->has('success'))
				<x-alert type="success">
					{{ session()->get('success') }}
				</x-alert>
			@endif
			<x-create-form-v noSubmitButton
				action="{{ route('termination.store') }}">
				<div class="card-header bg-secondary-subtle">
					<h3 class="text-center">New Termination</h3>
				</div>

				<div class="card-body">
					<div class="row">

						<div class="form-group col-md-6 p-2">
							<x-input-label for="ticket_number"
								:value="__('Ticket Number')" />
							<x-input-text name="ticket_number"
								value="{{ old('ticket_number') }}"
								:placeholder="__('Ticket Number')" />
							<x-input-error class="mt-2"
								:messages="$errors->get('ticket_number')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label class="required"
								for="requestor_name"
								:value="__('Requestor Name')" />
							<x-input-text name="requestor_name"
								value="{{ old('requestor_name') }}"
								:placeholder="__('Requestor Name')" />
							<x-input-error class="mt-2"
								:messages="$errors->get('requestor_name')" />
						</div>

						<x-dynamic-select class="col-md-6 p-2"
							required
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
							]" />

						<div class="form-group col-md-6 p-2">
							<x-input-label class="required"
								for="datetimepickerInput"
								:value="__('Request Date')" />

							<x-datetime-picker name="request_date"
								value="{{ old('request_date') }}" />
							<x-input-error class="mt-2"
								:messages="$errors->get('request_date')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label :value="__('Termination Remark')" />
							<textarea class="form-control"
							 id="editor"
							 name="remark"
							 style="height: 100px"></textarea>
							<x-input-error class="mt-2"
								:messages="$errors->get('remark')" />
						</div>

						<div class="form-group col-md-6 p-2">
							<x-input-label :value="__('Termination Type')"
								required />
							<div class="btn-group w-100"
								role="group"
								aria-label="staging options">
								<input class="btn-check"
									id="primary-outlined"
									name="termination_type"
									type="radio"
									value=0
									autocomplete="off">
								<label class="btn btn-outline-primary"
									for="primary-outlined">Terminate Only</label>

								<input class="btn-check"
									id="info-outlined"
									name="termination_type"
									type="radio"
									value=1
									autocomplete="off">
								<label class="btn btn-outline-info"
									for="info-outlined">Terminate & Renew</label>
							</div>
						</div>
						<!-- TODO: OPTIONAL : Add request category filter after
						<div class="card my-3 p-0" id="renewalUnit">
							<div class="card-header bg-info-subtle">
								<h5>Unit Renewal</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<x-dynamic-select class="serials renewalUnitSerial col-md-6 p-2" required :valueField="'unit_serial'" :label1="'Serial Number Renewal'" :labelField="'unit_serial'" :condition="'staging'" :parentName="'sn_renewal'" :paramUrl="'unit_serial'" :parentUrl="'api/fetch-unit-serials'" :childUrl="'api/fetch-units/staging'" :children="[
									    [
									        'name' => 'renew-unit_category',
									        'label' => 'Unit Category',
									        'childValue' => 'unit_category',
									        'inputType' => 'text',
									        'required' => false,
									        'disabled' => true,
									    ],
									    [
									        'name' => 'renew-service',
									        'label' => 'Service',
									        'childValue' => 'service',
									        'inputType' => 'text',
									        'required' => false,
									        'disabled' => true,
									    ],
									    [
									        'name' => 'renew-service_category',
									        'label' => 'Service Category',
									        'childValue' => 'service_category',
									        'inputType' => 'text',
									        'required' => false,
									        'disabled' => true,
									    ],
									    [
									        'name' => 'staging_id',
									        'childValue' => 'id',
									        'inputType' => 'hidden',
									        'required' => false,
									    ],
									]"/>
								</div>
							</div>
						</div>
-->

						<div class="card my-3 p-0"
							id="terminationUnit">
							<div class="card-header bg-primary-subtle"
								id="terminationUnitHeader">
								<h5>Unit Termination</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<x-dynamic-select class="serials col-md-6 p-2"
										required
										:valueField="'unit_serial'"
										:label1="'Serial Number Termination'"
										:labelField="'unit_serial'"
										:condition="'deployment'"
										:parentName="'sn_termination'"
										:paramUrl="'unit_serial'"
										:parentUrl="'api/fetch-unit-serials'"
										:childUrl="'api/fetch-units/deployment'"
										:children="[
										    [
										        'name' => 'holder_name',
										        'label' => 'Holder Name',
										        'childValue' => 'holder_name',
										        'inputType' => 'text',
										        'required' => false,
										        'readonly' => true,
										    ],
										    [
										        'name' => 'company_name',
										        'label' => 'Company Name',
										        'childValue' => 'company_name',
										        'inputType' => 'text',
										        'required' => false,
										        'disabled' => true,
										    ],
										    [
										        'name' => 'company_group',
										        'label' => 'Company group',
										        'childValue' => 'company_group',
										        'inputType' => 'text',
										        'required' => false,
										        'disabled' => true,
										    ],
										    [
										        'name' => 'location',
										        'childLabel' => 'location',
										        'label' => 'Location',
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
										        'name' => 'termination_company_id',
										        'childValue' => 'company_id',
										        'inputType' => 'hidden',
										        'required' => false,
										    ],
										    [
										        'name' => 'termination_company_address',
										        'childValue' => 'address_id',
										        'inputType' => 'hidden',
										        'required' => false,
										    ],
										    [
										        'name' => 'terminated_id',
										        'childValue' => 'id',
										        'inputType' => 'hidden',
										        'required' => false,
										    ],
										]" />
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

	@push('js')
		<script type="text/javascript">
			$(document).ready(function() {
				$('#renewalUnit').hide();
				$('input[name="termination_type"]').on('click', function() {
					if ($(this).val() === '1') {
						$('#renewalUnit').show();
						$('#terminationUnitHeader').addClass('bg-info-subtle');

					} else {
						$('#terminationUnitHeader').removeClass('bg-info-subtle');
						$('#sn_renewal-dropdown').html('');
						$('#renew-unit_category-text').val('');
						$('#renew-service-text').val('');
						$('#renew-service_category-text').val('');
						$('#renewalUnit').hide();
					}
				});
			});
		</script>
	@endpush

</x-app-layout>
