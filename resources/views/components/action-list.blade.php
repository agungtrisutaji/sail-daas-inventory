@props(['actions' => [], 'type' => '', 'label' => 'Actions'])

@if (count($actions) > 0)
	<div class="dropstart">
		@if ($type == 'button')
			<button data-bs-toggle="dropdown"
				type="button"
				aria-expanded="false"
				{{ $attributes->merge(['class' => 'btn btn-primary dropdown-toggle text-decoration-none mx-auto']) }}>
				{{ $label }}
			</button>
		@else
			<a class="dropdown-toggle text-decoration-none mx-auto"
				data-bs-toggle="dropdown"
				type="button"
				aria-expanded="false">
				{{ $label }}
			</a>
		@endif
		<ul class="dropdown-menu">
			@foreach ($actions as $key => $action)
				<li>
					@if (isset($action['method']) && $action['method'] !== 'GET')
						<form class="d-inline"
							action="{{ $action['url'] }}"
							method="POST">
							@csrf
							@method($action['method'])
							<button class="dropdown-item"
								type="submit"
								@if (isset($action['confirm'])) data-confirm-delete="true" @endif>
								{{ $action['label'] }}
							</button>
						</form>
					@elseif(isset($action['modal']) && $action['modal'])
						<a class="dropdown-item {{ $action['disabled'] ?? false ? 'disabled' : '' }}"
							data-bs-toggle="modal"
							data-bs-target="#{{ $action['modalName'] }}"
							data-url="{{ $action['url'] }}"
							href="#"
							aria-disabled="{{ $action['disabled'] ?? false ? 'true' : 'false' }}">{{ $action['label'] }}</a>
					@else
						<a class="dropdown-item {{ $action['disabled'] ?? false ? 'disabled' : '' }}"
							href="{{ $action['url'] }}"
							aria-disabled="{{ $action['disabled'] ?? false ? 'true' : 'false' }}">{{ $action['label'] }}</a>
					@endif
				</li>
			@endforeach
		</ul>
	</div>
@endif
