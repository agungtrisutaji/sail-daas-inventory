@props([
    'id' => null,
    'action' => '',
    'method' => 'POST',
    'color' => null,
    'submitButtonText' => null,
    'noSubmitButton' => false,
])

<form id="{{ $id ?? '' }}"
	{!! $attributes->merge(['class' => ' ']) !!}
	action="{{ $action }}"
	method="{{ $method === 'GET' ? 'GET' : 'POST' }}">
	@csrf
	@if ($method !== 'GET' && $method !== 'POST')
		@method($method)
	@endif
	{{ $slot }}
	@if ($noSubmitButton !== true)
		<button class="btn btn-{{ $color ?? 'primary' }}"
			type="submit">{{ $submitButtonText ?? 'Submit' }}</button>
	@endif
</form>
