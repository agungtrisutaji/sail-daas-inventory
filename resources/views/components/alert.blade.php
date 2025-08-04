{{-- resources/views/components/alert.blade.php --}}
@props(['type' => 'success', 'color' => 'success'])

@if ($message = Session::get($type))
	<div class="alert alert-{{ $color }} alert-dismissible fade show">
		{!! $message !!}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
@endif
