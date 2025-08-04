@props(['disabled' => false, 'readonly' => false, 'name', 'value' => ''])

<input id="{{ $name }}"
	name="{{ $name }}"
	value="{{ $value }}"
	{{ $disabled ? 'disabled' : '' }}
	{!! $attributes->merge(['class' => 'form-control ']) !!}
	{{ $readonly ? 'readonly' : '' }} />
