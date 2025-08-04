@props([
    'withSelect' => false,
    'withAssetGroup' => false,
    'name',
    'title',
    'route',
    'method',
    'fields' => [],
    'parentUrl' => 'api/fetch-companies',
    'submitButtonText' => 'Submit',
    'otherButton' => [],
    'withOperationalUnit' => false,
    'withDate' => false,
    'dateFields' => [],
    'secondModal' => false,
])

<!-- Modal trigger button -->
<button data-bs-toggle="modal"
	data-bs-target=".{{ $name }}-modal"
	type="button"
	{{ $attributes->merge(['class' => 'btn import-btn']) }}>
	{{ $title }}
</button>

<!-- Modal -->
<div class="modal fade {{ $name }}-modal modal-lg"
	id="import-modal"
	aria-labelledby="{{ $name . 'Label' }}"
	aria-modal="true"
	tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="importForm"
				method="{{ $method }}"
				action="{{ $route }}"
				enctype="multipart/form-data">
				@csrf
				<div class="modal-header">
					<h1 class="modal-title fs-5"
						id="{{ $name . 'Label' }}">{{ $title }}</h1>
					<button class="btn-close"
						data-bs-dismiss="modal"
						type="button"
						aria-label="Close"></button>
				</div>
				<div class="modal-body">
					@csrf
					<div class="card-body">
						<div class="mb-3">
							@if ($withOperationalUnit)
								<x-dynamic-select :valueField="'id'"
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
							@endif

							@if ($withSelect)
								@foreach ($fields as $field)
									@php
										$model = $field['model'] ?? null;
										$options = $field['options'] ?? null;
										$label = $field['label'] ?? null;
										$valueField = $field['valueField'] ?? 'id';
										$labelField = $field['labelField'] ?? 'company_name';
									@endphp
									<div class="form-group p-2">
										<x-input-label for="upgrade_type required"
											for="{{ $field['name'] }}-input"
											:value="$label" />
										<select class="form-select import-select"
											id="{{ $field['name'] }}-select"
											name="{{ $field['name'] }}">
											<option value="">Select {{ ucfirst($label) }}</option>
											@if (isset($model) && isset($valueField) && isset($labelField))
												@foreach ($model as $item)
													<option value="{{ $item->$valueField }}">
														{{ $item->$labelField }}
													</option>
												@endforeach
											@elseif ($options)
												@foreach ($options as $key => $option)
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
											@endif

										</select>
									</div>
								@endforeach
							@endif

							@if ($withDate)
								@foreach ($dateFields as $dateField)
									@php
										$label = $dateField['label'];
									@endphp
									<div class="input-group my-3">
										<x-input-label for="{{ $dateField['name'] }}"
											:value="$label" />
										<x-datetime-picker name="{{ $dateField['name'] }}" />
									</div>
								@endforeach
							@endif

							@if ($withAssetGroup)
								<div class="form-group p-2">

									<x-input-label :value="__('Import Unit For')" />

									<div class="btn-group w-100 mb-2"
										role="group"
										aria-label="process import unit">
										<input class="btn-check"
											id="primary-outlined"
											name="asset_group"
											type="radio"
											value="1"
											autocomplete="off"
											checked>
										<label class="btn btn-outline-primary"
											for="primary-outlined">Daas</label>

										<input class="btn-check"
											id="success-outlined"
											name="asset_group"
											type="radio"
											value="2"
											disabled
											autocomplete="off">
										<label class="btn btn-outline-success"
											for="success-outlined">BreakFix</label>

										<input class="btn-check"
											id="warning-outlined"
											name="asset_group"
											type="radio"
											value="3"
											disabled
											autocomplete="off">
										<label class="btn btn-outline-warning"
											for="warning-outlined">Backup</label>

									</div>
									<div class="mt-3"
										id="dynamicInput">
										<!-- Input fields will be dynamically inserted here -->
									</div>
								</div>
							@endif

							<div class="input-group my-3">
								<input class="form-control"
									id="inputGroupFile"
									name="file"
									type="file">
								<label class="input-group-text"
									for="inputGroupFile02">Upload</label>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col">
						<button class="btn btn-secondary"
							data-bs-dismiss="modal"
							type="button">Close</button>

						@if ($secondModal)
							<button class="btn btn-outline-primary ms-1"
								data-bs-target="#{{ $name }}-modal-2"
								data-bs-toggle="modal"
								type="button">All in one File</button>
						@endif
					</div>
					<div class="spinner-grow spinner d-none text-primary"
						role="status">
						<span class="visually-hidden">Loading...</span>
					</div>

					<div class="col d-flex justify-content-end">
						@if ($otherButton)

							@if ($otherButton['link'])
								<a class="btn btn-{{ $otherButton['color'] }} me-2"
									href="{{ $otherButton['link'] }}">{{ $otherButton['text'] }}</a>
							@else
								<button class="btn btn-primary"
									type="submit">{{ $otherButton['text'] }}</button>
							@endif
						@endif

						<button class="btn btn-primary"
							type="submit">{{ $submitButtonText }}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

@push('js')
	<script type="text/javascript">
		$('.import-select').select2({
			dropdownParent: $('#import-modal'),
			theme: 'bootstrap-5',
			allowClear: false,
			language: 'en',
		})
	</script>

	<script>
		$(document).ready(function() {
			$('#importForm').on('submit', function(e) {
				// Disable submit button
				$('.btn').addClass('disabled');
				$('.spinner').removeClass('d-none');
			});
		});
	</script>

	{{-- <script>
		$(document).ready(function() {
			function updateInput(value) {
				var inputHTML = '';
				switch (value) {
					case '1':
						inputHTML =
							'<input type="text" id="daas_text" name="daas_text" class="form-control" placeholder="Input something for Daas">';
						break;
					case '2':
						inputHTML =
							'<input type="text" class="form-control" id="breakfix_text" name="breakfix_text" placeholder="Input something for BreakFix">';
						break;
					case '3':
						inputHTML =
							'<input type="text" class="form-control" id="backup_text" name="backup_text" placeholder="Input something for Backup">';
						break;
				}
				$('#dynamicInput').html(inputHTML);
			}

			$('input[name="asset_group"]').on('change', function() {
				updateInput($(this).val());
			});

			// Initialize with the default checked value
			updateInput($('input[name="asset_group"]:checked').val());
		});
	</script> --}}
@endpush
