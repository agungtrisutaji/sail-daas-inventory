{{-- resources/views/components/export-modal.blade.php --}}
@props([
    'modalId' => 'export-modal',
    'title' => 'Export With Selected Columns',
    'description' => 'Switching the columns off will be excluded from the exported file.',
    'fields' => [],
    'columns' => [],
    'exportRoute' => '',
    'options' => null,
    'selected' => '',
    'model' => null,
    'valueField' => 'code',
    'labelField' => 'label',
    'dateRange' => false,
    'dateOptions' => [],
])

<button class="btn export-btn btn-outline-success"
	data-bs-toggle="modal"
	data-bs-target="#{{ $modalId }}"
	type="button">
	Export
</button>

<div class="modal fade"
	id="{{ $modalId }}"
	aria-labelledby="{{ $modalId }}-label"
	aria-hidden="true"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="row">
					<h1 class="modal-title fs-5"
						id="{{ $modalId }}-label">{{ $title }}</h1>
					<div class="form-text"
						id="form-text">
						{{ $description }}
					</div>
				</div>
				<button class="btn-close align-self-start"
					data-bs-dismiss="modal"
					type="button"
					aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="row gy-2 gx-3 align-items-center"
					action="{{ $exportRoute }}"
					method="GET">
					<label>Custom File Name :</label>
					<div class="input-group mb-3">
						<x-input-text name="file_name"
							:value="request('file_name')"
							placeholder="Inventory_{{ Carbon\Carbon::now()->format('Y_m_d_H_i') }}" />
						<span class="input-group-text"
							id="basic-addon2">.xlsx</span>
					</div>
					<hr>
					<label>Columns to export:</label>
					<div class="row mx-auto p-2">
						@foreach ($columns as $key => $column)
							<div class="form-check form-switch col-md-6">
								<input class="form-check-input"
									id="btncheck{{ $key }}"
									name="columns[]"
									type="checkbox"
									value="{{ $column }}"
									autocomplete="off">
								<label class="form-check-label text-nowrap"
									for="btncheck{{ $key }}">{{ $column }}</label>
							</div>
						@endforeach
					</div>
					<hr>
					<label>Apply Filters :</label>
					<div class="row mx-auto mb-3">
						@foreach ($fields as $field)
							<div class="form-group col-md-6 p-2">
								<x-input-label for="{{ $field['name'] }}"
									:value="__($field['label'])" />
								@if ($field['type'] === 'text')
									<x-text-input name="{{ $field['name'] }}"
										:value="request($field['name'])" />
								@elseif($field['type'] === 'select')
									<x-select-input name="{{ $field['name'] }}"
										:label="$field['label']"
										:model="$field['model'] ?? null"
										:options="$field['options'] ?? []"
										:selected="request($field['name'])" />
								@endif
							</div>
						@endforeach

						@if ($dateRange)
							<div class="dropdown-divider"></div>

							<div class="form-group col-md-12 mx-auto p-2">
								<x-input-label class="text-center"
									for="date_column"
									:value="__('Date Range')" />
								<x-select-input class="w-100"
									name="date_column"
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
					<div class="d-flex justify-content-center">
						<button class="btn btn-primary mt-2"
							id="export-btn"
							type="submit">Export to Excel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
