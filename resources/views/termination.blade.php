<x-app-layout>

	<x-slot name="header">
		{{ __('Termination') }}
	</x-slot>

	<div class="container-fluid"
		id="terminationContainer"
		data-url="{{ route('termination.data') }}">
		<div class="row">

			<div class="col">
				<x-dropdown-filter :fields="[
				    ['name' => 'serial', 'label' => 'Serial Number', 'type' => 'text'],
				    ['name' => 'brand', 'label' => 'Brand', 'type' => 'text'],
				]" />
			</div>

			<div class="col d-flex justify-content-end">

				<div class="btn-group mb-2"
					role="group"
					aria-label="process upgrade">

					<a class="btn btn-outline-primary"
						type="button"
						href="{{ route('termination.create') }}">Create Termination</a>

				</div>

			</div>
		</div>

		<x-alert type="success" />
		<x-alert type="error"
			color="danger" />

		<hr class="rounded-pill border-3 border opacity-75">

		@include('terminations.index')
		@include('components.create-modal', ['modalTitle' => 'Update Transfer Progress'])

		@push('js')
			<script type="module"
				src="{{ Vite::asset('resources/js/staging-init.js') }}"></script>
		@endpush

	</div>

</x-app-layout>
