@props([
    'model',
    'text' => null,
    'parentName',
    'paramUrl',
    'parentUrl',
    'childName',
    'childUrl',
    'label1' => '',
    'label2' => '',
    'valueField' => 'id',
    'labelField' => 'name',
    'children' => null,
    'modalId' => '',
    'required' => false,
    'labelCount' => false,
    'count' => null,
    'countRelations' => null,
    'wFull' => false,
    'dump' => false,
    'readonly' => false,
    'condition' => null,
])

<div {!! $attributes->merge(['class' => 'form-group ']) !!}>
	<label class='col-form-label {{ $required ? 'required' : '' }}'
		for="{{ $parentName }}-dropdown">
		{{ ucfirst($label1) }}
	</label>

	@if ($text)
		<span class="form-text text-muted">{{ $text }}</span>
	@endif
	<select class="form-control parent-input"
		id="{{ $parentName }}-dropdown"
		name="{{ $parentName }}">
		<option value=""></option>
	</select>

	<x-input-error class="mt-2"
		:messages="$errors->get($parentName)" />
</div>

@if ($children)
	@foreach ($children as $child)
		@php
			$name = $child['name'];
			$childRequired = $child['required'] ?? true;
			$childSelected = $child['selected'] ?? null;
			$childReadonly = $child['readonly'] ?? false;
			$childDisabled = $child['disabled'] ?? false;
		@endphp
		<div {!! $attributes->merge(['class' => 'form-group ']) !!}>
			@if (isset($child['label']))
				<label class='col-form-label {{ $childRequired ? 'required' : '' }}'
					for="{{ $child['name'] }}-input">
					{{ ucfirst($child['label']) }}
				</label>
			@endif

			@if ($child['inputType'] === 'select')
				<select class="form-control"
					id="{{ $child['name'] }}-select"
					name="{{ $child['name'] }}"
					aria-describedby="{{ $child['name'] }}-select"
					{{ $childReadonly ? 'readonly' : '' }}
					{{ $childDisabled ? 'disabled' : '' }}>
				</select>
			@else
				<input class="form-control child-input"
					id="{{ $child['name'] }}-text"
					name="{{ $child['name'] }}"
					type="{{ $child['inputType'] }}"
					aria-describedby="{{ $child['name'] }}-text"
					{{ $childDisabled ? 'disabled' : '' }}
					{{ $childReadonly ? 'readonly' : '' }} />
			@endif

			<x-input-error class="mt-2"
				:messages="$errors->get($child['name'])" />
		</div>
	@endforeach
@endif

@push('js')
	<script type="text/javascript">
		$('#{{ $parentName }}-dropdown').select2({
			placeholder: "Select {{ ucfirst($label1) }}",
			theme: 'bootstrap-5',
			allowClear: true,
			ajax: {
				url: '{{ url($parentUrl) }}',
				type: "POST",
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						condition: '{{ $condition }}',
						// TODO: OPTIONAL : Add request category filter after
						search: params.term,
						page: params.page || 1,
						_token: '{{ csrf_token() }}'
					};
				},
				processResults: function(data, params) {
					params.page = params.page || 1;

					return {
						results: data.data.map(function(item) {
							return {
								id: item.{{ $valueField }},
								text: item.{{ $labelField }} +
									@if ($labelCount)
										' (' + item.{{ $countRelations }}_count + ')'
									@else
										''
									@endif
							};
						}),
						pagination: {
							more: data.current_page < data.last_page
						}
					};
				},
				cache: true
			}
		}).on('change', function() {
			@if ($children)

				let param = $(this).val();
				let childUrl = '{{ $childUrl }}'; // URL API


				@if ($child['inputType'] === 'select')
					$('#{{ $child['name'] }}-select').html('').trigger('change');
				@else
					$('#{{ $child['name'] }}-text').val('').trigger('change');
				@endif

				if (param) {
					$.ajax({
						url: "{{ url($childUrl) }}",
						type: "POST",
						data: {
							{{ $paramUrl ?? $parentName }}: param,
							_token: '{{ csrf_token() }}'
						},
						dataType: 'json',
						success: function(response) {
							function truncateByCharacters(str, maxChars) {
								if (str.length <= maxChars) return str;
								return str.substr(0, maxChars) + "...";
							}

							if (response.data) {
								@if ($child['inputType'] === 'select')
									@if (!isset($child['selected']))
										$("#{{ $child['name'] }}-select").html(
											'<option value=""></option>').trigger('change');
									@else
										let selectedValue = response.data
											.{{ $child['selected']['value'] }}
										let selectedLabel = response.data
											.{{ $child['selected']['label'] }}

										$("#{{ $child['name'] }}-select").html(
												'<option value="' + selectedValue + '"> ' +
												truncateByCharacters(selectedLabel, 50) + '</option>')
											.trigger(
												'change');
										if (response.data.{{ $child['childValue'] }} === null) {

											$("#{{ $child['name'] }}-select").html(
												'').trigger('change');
										}
									@endif

									$.each(response.data, function(key, value) {
										@if (isset($child['childDetail']))
											let detail = ' - ' + truncateByCharacters(value
												.{{ $child['childDetail'] }}, 60);
										@else
											let detail = '';
										@endif
										$("#{{ $child['name'] }}-select").append(
											'<option value="' +
											value.{{ $child['childValue'] }} +
											'">' +
											truncateByCharacters(value
												.{{ $child['childLabel'] }},
												50) + detail +
											'</option>');
									});
								@else
									@foreach ($children as $child)
										if (response.data.{{ $child['childValue'] }} !== null) {
											$("#{{ $child['name'] }}-text").val(truncateByCharacters(
												response
												.data
												.{{ $child['childValue'] }}, 100));
										} else {
											$("#{{ $child['name'] }}-text").val('');
										}
									@endforeach
								@endif
							} else {
								// Reset if there's no data
								@foreach ($children as $child)
									$('#{{ $child['name'] }}').val('').trigger('change');
								@endforeach
							}
						},
						error: function() {
							console.error('Gagal memuat data');
						}
					});
				}
			@endif

		});

		@if ($children)
			@if ($child['inputType'] === 'select')
				$('#{{ $child['name'] }}-select').select2({
					placeholder: "Select {{ ucfirst($child['label']) }}",
					theme: 'bootstrap-5',
					allowClear: true
				});
			@endif
		@endif
	</script>
@endpush
