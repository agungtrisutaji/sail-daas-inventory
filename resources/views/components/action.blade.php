@props(['url' => '', 'label', 'method' => null, 'confirm' => null, 'modal' => null, 'modalName' => ''])

@if (isset($method) && $method !== 'GET')
	<form class="d-inline"
		action="{{ $url }}"
		method="POST">
		@csrf
		@method($method)
		<button type="submit"
			@if (isset($confirm)) data-confirm-delete="true" @endif>
			{{ $label }}
		</button>
	</form>
@elseif(isset($modal) && $modal)
	<a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
		data-bs-toggle="modal"
		data-bs-target="#{{ $modalName }}"
		data-url="{{ $url }}"
		href="#">{{ $label }}</a>
@else
	<a href="{{ $url }}">{{ $label }}</a>
@endif
