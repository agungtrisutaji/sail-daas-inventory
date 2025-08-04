@props([
    'options' => null,
    'model' => null,
    'valueField' => 'code',
    'labelField' => 'label',
    'placeholder' => '',
    'selected' => '',
    'modalId' => '',
])
<select class="form-select"
	id="{{ $name }}"
	name="{{ $name }}"
	data-placeholder="{{ $placeholder }}">
	<option value=""></option>
	@if ($model)
		@foreach ($model as $item)
			<option value="{{ $item->$valueField }}">
				{{ $item->$labelField }}
			</option>
		@endforeach
	@elseif ($options)
		@foreach ($options as $key => $option)
			@if (is_array($option))
				<option value="{{ $option['value'] ?? $key }}"
					{{ $selected == ($option['value'] ?? $key) ? 'selected' : '' }}>
					{{ $option['label'] }}
				</option>
			@else
				<option value="{{ $key }}"
					{{ $selected == $key ? 'selected' : '' }}>
					{{ $option }}
				</option>
			@endif
		@endforeach
	@endif

</select>

@push('js')
	<script type="text/javascript">
		$('#{{ $name }}').select2({
			dropdownParent: $('#{{ $modalId }}'),
			theme: 'bootstrap-5',
			placeholder: '{{ $placeholder }}',
			allowClear: false,
			language: 'en',
		})
	</script>
@endpush
