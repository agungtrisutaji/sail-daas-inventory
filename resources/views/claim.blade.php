<x-app-layout>

	<x-slot name="header">
		{{ __('Claim') }}
	</x-slot>
	<div class="d-flex justify-content-center container p-5">
		@include('components.maintenance')
	</div>
</x-app-layout>
