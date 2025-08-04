@props([
    'size' => 'lg',
])

<div class="modal fade"
	id="{{ $id }}"
	aria-labelledby="{{ $id }}Label"
	aria-hidden="true"
	tabindex="-1">
	<div class="modal-dialog modal-{{ $size }}">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"
					id="{{ $id }}Label">{{ $title }}</h5>
				<button class="btn-close"
					data-bs-dismiss="modal"
					type="button"
					aria-label="Close"></button>
			</div>
			<div class="modal-body">
				{{ $slot }}
			</div>
		</div>
	</div>
</div>
