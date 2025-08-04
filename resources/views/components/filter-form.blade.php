@props([
    'fields' => [],
    'action' => '',
    'formId' => 'filter-form',
    'dateRange' => false,
    'dateOptions' => [],
    'withButton' => true,
    'noReset' => false,
    'companyModel' => null,
    'companyLabel' => 'Company',
    'valueField' => 'id',
    'labelField' => 'company_name',
    'labelCount' => true,
    'countRelations' => 'addresses',
    'parentName' => 'company_id',
    'parentUrl' => 'api/fetch-companies',
    'childName' => 'distributor_id',
    'childUrl' => 'api/fetch-locations',
    'childInputLabel' => 'Location',
    'childLabel' => 'location',
    'childValue' => 'id',
    'childType' => 'select',
    'childRequired' => false,
    'hasChild' => true,
])

<form class="px-4 py-3"
	id="{{ $formId }}"
	action="{{ $action }}"
	method="GET">
	<div class="row mb-3">
		@foreach ($fields as $field)
			<div class="form-group col-md-6 p-2">
				@if ($field['type'] === 'text')
					<x-input-label for="{{ $field['name'] }}"
						:value="__($field['label'])" />
					<x-text-input name="{{ $field['name'] }}"
						:value="request($field['name'])" />
				@elseif($field['type'] === 'select')
					<x-select-input name="{{ $field['name'] }}"
						:valueField="$field['valueField'] ?? 'code'"
						:labelField="$field['labelField'] ?? 'label'"
						:label="$field['label'] ?? null"
						:model="$field['model'] ?? null"
						:options="$field['options'] ?? []"
						:selected="request($field['name'])" />
                @elseif($field['type'] === 'date')
                    <x-input-label for="{{ $field['name'] }}"
                        :value="__($field['label'])" />
                    <x-text-input name="{{ $field['name'] }}"
                        type="date"
                        :value="request($field['name'])" />
				@endif
			</div>
		@endforeach

		@if ($companyModel)
			@php
				$children = [];
				if ($hasChild) {
				    $children = [
				        [
				            'name' => $childName,
				            'label' => $childInputLabel,
				            'childValue' => $childValue,
				            'childLabel' => $childLabel,
				            'inputType' => $childType,
				            'required' => $childRequired,
				        ],
				    ];
				}
			@endphp

			<x-dynamic-select class="col-md-6 p-2"
				:model="$companyModel"
				:valueField="$valueField"
				:label1="__($companyLabel)"
				:labelField="$labelField"
				:labelCount="$labelCount"
				:countRelations="$countRelations"
				:parentName="$parentName"
				:parentUrl="$parentUrl"
				:childUrl="$childUrl"
				:children="$children" />
		@endif

		@if ($dateRange)
			<div class="dropdown-divider"></div>

			<div class="form-group col-md-12 mx-auto p-2">
				<x-input-label class="text-center"
					for="date_column"
					:value="__('Date Range')" />
				<x-select-input class="w-100"
					name="date_column"
					:label="__('Date Range')"
					:options="$dateOptions"
					:selected="request()->date_column" />
			</div>

			<div class="form-group col-md-6 p-2">
				<x-input-label for="start_date"
					:value="__('Start Date')" />
				<x-text-input name="start_date"
					type="date"
					:value="request()->start_date" />
			</div>
			<div class="form-group col-md-6 p-2">
				<x-input-label for="end_date"
					:value="__('End Date')" />
				<x-text-input name="end_date"
					type="date"
					:value="request()->end_date" />
			</div>
		@endif
	</div>

	{{-- <div class="form-group col-md-6 p-2"></div> --}}
	@if ($withButton)
		<div class="row d-flex justify-content-end">
			<div class="col-md-3">
				@if ($noReset)
					<label>&nbsp;</label>
				@else
					<label>&nbsp;</label>
					<button class="btn btn-success form-control"
						id="reset-filter"
						type="submit">Reset</button>
				@endif
			</div>
			<div class="col-md-3">
				<label>&nbsp;</label>
				<button class="btn btn-primary form-control"
					type="submit">Filter</button>
			</div>
		</div>
	@endif

</form>
