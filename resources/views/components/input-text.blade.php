<input id="{{ $name }}"
	name="{{ $name }}"
	type="text"
	value="{{ $value }}"
	placeholder="{{ $placeholder }}"
	{{ $attributes->merge(['class' => 'form-control ']) }}
	{{ isset($required) ? 'required' : '' }} />
