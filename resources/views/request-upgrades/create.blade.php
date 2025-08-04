<x-app-layout>

    <x-slot name="header">
        {{ __('Create New Request Upgrade') }}
    </x-slot>

    <div class="container">
        <div class="card">
            <x-alert type="success"
                     color="success" />
            <x-alert type="error"
                     color="danger" />
            <x-create-form-v class="p-3"
                             action="{{ route('upgrade.store') }}">
                <div class="row mb-2">
                    <div class="form-group col-md-6 p-2">
                        <x-select-input name="unit_serial"
                                        :label="__('Serial Number')"
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
                                      :label1="__('Company')"
                                      :labelField="'company_name'"
                                      :labelCount="true"
                                      :countRelations="'addresses'"
                                      :parentName="'company_id'"
                                      :parentUrl="'api/fetch-companies'"
                                      :childUrl="'api/fetch-locations'"
                                      :children="[
						    [
						        'name' => 'company_address',
						        'label' => __('Company Location'),
						        'childValue' => 'id',
						        'childLabel' => 'location',
						        'inputType' => 'select',
						    ],
						]" />
                    <div class="col-md-6">

                        <x-input-error :messages="$errors->get('company_name')" />
                    </div>
                    <div class="col-md-6">
                        <x-input-error :messages="$errors->get('company_address')" />
                    </div>

                    <x-dynamic-select class="col-md-6 p-2"
                                      :model="$operationalUnits"
                                      :valueField="'id'"
                                      :label1="__('Operational Unit')"
                                      :labelField="'company_name'"
                                      :labelCount="true"
                                      :countRelations="'addresses'"
                                      :parentName="'operational_unit_id'"
                                      :parentUrl="'api/fetch-operational-units'"
                                      :childUrl="'api/fetch-locations'"
                                      :children="[
						    [
						        'name' => 'operational_unit_address',
						        'label' => __('Operational Location'),
						        'childValue' => 'id',
						        'childLabel' => 'location',
						        'inputType' => 'select',
						    ],
						]" />

                    <div class="col-md-6">
                        <x-input-error :messages="$errors->get('operational_unit_id')" />
                    </div>
                    <div class="col-md-6">
                        <x-input-error :messages="$errors->get('operational_unit_address')" />
                    </div>

                    <div class="form-group col-md-6 p-2">
                        <x-select-input name="upgrade_type"
                                        :options="$upgradeTypes"
                                        :label="__('Upgrade Type')" />
                        <x-input-error class="mt-2"
                                       :messages="$errors->get('upgrade_type')" />
                    </div>

                    <div class="py-3 ps-1">
                        <x-input-label for="requestor"
                                       :value="__('Upgrade Remark')" />
                        <textarea class="form-control"
                                  id="editor"
                                  name="upgrade_remark"></textarea>
                        <x-input-error class="mt-2"
                                       :messages="$errors->get('upgrade_remark')" />
                    </div>

                    <div class="form-group col-md-6 p-2">
                        <x-input-label for="engineer"
                                       :value="__('Engineer')" />
                        <x-input-text name="engineer"
                                      :placeholder="__('Enter Engineer')" />
                        <x-input-error class="mt-2"
                                       :messages="$errors->get('engineer')" />
                    </div>

                    <div class="form-group col-md-6 p-2">
                        <x-input-label for="datetimepickerInput"
                                       :value="__('BAST Date')" />

                        <x-datetime-picker name="bast_date"
                                           value="{{ old('bast_date') }}"
                                           required />
                        <x-input-error class="mt-2"
                                       :messages="$errors->get('bast_date')" />
                    </div>

                    <div class="form-group col-md-6 p-2">
                        <x-input-label for="offering_price"
                                       :value="__('Offering Price')" />
                        <x-input-currency name="offering_price" />
                        <x-input-error class="mt-2"
                                       :messages="$errors->get('offering_price')" />
                    </div>

                    <div class="form-group col-md-6 p-2">
                        <x-input-label for="part_expense"
                                       text="( Part only )"
                                       :value="__('Part Expense')" />
                        <x-input-currency name="part_expense" />
                        <x-input-error class="mt-2"
                                       :messages="$errors->get('part_expense')" />
                    </div>

                    <div class="form-group col-md-6 p-2">
                        <x-input-label for="engineer_expense"
                                       text="( Include mandays & accommodation )"
                                       :value="__('Engineer Expense')" />
                        <x-input-currency name="delivery_expense"
                                          value="{{ old('delivery_expense', $expense ?? '') }}" />
                        <x-input-error class="mt-2"
                                       :messages="$errors->get('engineer_expense')" />
                    </div>

                    <div class="form-group col-md-6 p-2">
                        <x-input-label for="delivery_expense"
                                       text="( Include delivery cost) "
                                       :value="__('Delivery Expense')" />
                        <x-input-currency name="delivery_expense" />
                        <x-input-error class="mt-2"
                                       :messages="$errors->get('delivery_expense')" />
                    </div>
                    {{-- <div class="row d-flex justify-content-end mx-0 p-0">
                        <div class="form-group col-md-6 p-2">
                            <x-input-label for="total_expense"
                                           :value="__('Total Expense')" />
                            <x-input-currency name="total_expense" />
                        </div>
                    </div> --}}

                </div>
            </x-create-form-v>
        </div>
    </div>
</x-app-layout>
