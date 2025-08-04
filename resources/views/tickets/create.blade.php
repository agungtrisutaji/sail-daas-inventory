<div class="row mb-2"
	data-modal="CreateModal">

	<div class="form-group col-md-6 p-2">
		<x-input-label for="unit_serial"
			:value="__('Serial Number')" />
		<x-input-select name="unit_serial"
			:modalId="'ticketCreateModal'"
			:model="$units"
			:valueField="'serial'"
			:labelField="'serial'"
			:placeholder="__('Serial Number')" />
		<x-input-error class="mt-2"
			:messages="$errors->get('unit_serial')" />
	</div>

	<div class="form-group col-md-6 p-2">
		<x-input-label for="ticket_number"
			:value="__('Ticket Number')" />
		<x-input-text name="ticket_number"
			:placeholder="__('Ticket Number')" />
		<x-input-error class="mt-2"
			:messages="$errors->get('ticket_number')" />
	</div>

	<div class="form-group col-md-6 p-2">
		<x-input-label for="caller"
			:value="__('Caller')" />
		<x-input-text name="caller"
			:placeholder="__('Caller')" />
		<x-input-error class="mt-2"
			:messages="$errors->get('caller')" />
	</div>

	<div class="form-group col-md-6 p-2">
		<x-input-label for="requestor"
			:value="__('Requestor')" />
		<x-input-text name="requestor"
			:placeholder="__('Requestor')" />
		<x-input-error class="mt-2"
			:messages="$errors->get('requestor')" />
	</div>

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
		]" />

	<div class="form-group col-md-6 p-2">
		<x-input-label for="ticket_type"
			:value="__('Ticket Type')" />
		<x-input-select name="ticket_type"
			:modalId="'ticketCreateModal'"
			:options="$ticketTypes"
			:valueField="'code'"
			:labelField="'label'"
			:placeholder="__('Select Type')" />
		<x-input-error class="mt-2"
			:messages="$errors->get('ticket_type')" />
	</div>

	<div class="p-2">
		<x-input-label for="ticket_remark"
			:value="__('Ticket Remark')" />
		<textarea class="form-control"
		 id="editor"
		 name="ticket_remark"></textarea>
		<x-input-error class="mt-2"
			:messages="$errors->get('ticket_remark')" />
	</div>

</div>
