<x-app-layout>
	<x-slot name="header">
		{{ __('Deployment') }}
	</x-slot>

	<div class="container-fluid"
		id="deploymentContainer"
		data-url="{{ route('deployment.data') }}">
		{{-- <div class="row">
			<div class="col d-flex justify-content-end">

				<x-import-form class="btn-primary my-2"
					:name="'import-deployments'"
					:title="'Import Deployments'"
					:method="'POST'"
					:route="route('deployment.upload.preview')"
					:buttonText="'Upload for Preview'" />
				<---! <a href="{{ route('deployment.import.form') }}" class="btn btn-primary">Import Deployment Data</a> -->
			</div>
		</div> --}}

		<x-alert type="success" />
		<x-alert type="error"
			color="danger" />

		@foreach ($errors->all() as $error)
			<div class="alert alert-danger alert-dismissible fade show">
				<ul>
					<li>{{ $error }}</li>
				</ul>
			</div>
		@endforeach

		<hr class="rounded-pill border-3 border opacity-75">

		@include('deployments.index')
		@include('components.create-modal', ['modalTitle' => 'Update Deployment'])

	</div>

	@push('js')
		<script type="module"
			src="{{ Vite::asset('resources/js/deployment-init.js') }}"></script>
	@endpush
</x-app-layout>

{{-- //TODO: OPSI : make sure semua feature mana saja yang membutuhkan import excel --}}
