@props(['value', 'text' => null, 'required' => false])

<label {{ $attributes->merge(['class' => ' col-form-label']) }}>
	{{ $value ?? $slot }}
</label>

@if ($required)
	<span class="text-danger">*</span>
@endif

@if ($text)
	<span class="form-text text-muted">{{ $text }}</span>
@endif
