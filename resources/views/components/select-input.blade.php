@props([
    'name',
    'options' => null,
    'selected' => '',
    'model' => null,
    'valueField' => 'code',
    'labelField' => 'label',
    'label' => null,
    'disabled' => false,
])

@if ($label)
	<x-input-label for="{{ $name }}"
		:value="$label" />
@endif

<select id="{{ $name }}"
	name="{{ $name }}"
	{{ $disabled ? 'disabled' : '' }}
	{!! $attributes->merge(['class' => 'form-select ']) !!}>
	<option value=""></option>
	@if ($model)
		@foreach ($model as $item)
			<option value="{{ $item->$valueField }}"
				{{ $selected == $item->$valueField ? 'selected' : '' }}>
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
			theme: 'bootstrap-5',
			allowClear: true,
			language: 'en',
			placeholder: 'Select {{ ucfirst($label) }}',
		})
	</script>
@endpush
