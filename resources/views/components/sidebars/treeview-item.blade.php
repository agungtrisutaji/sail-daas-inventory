@section('treeview-title')
    <p>Operations</p>
@endsection
@section('treeview-items')
		<x-sidebars.sidebar-link href="/deliveries" active="{{ request()->routeIs('deliveries.*') }}">
			<i class="bi bi-arrow-return-right nav-icon"></i>
			<p>Delivery</p>
		</x-sidebars.sidebar-link>
		<x-sidebars.sidebar-link href="/stagings" active="{{ request()->routeIs('stagings.*') }}">
			<i class="bi bi-arrow-return-right nav-icon"></i>
			<p>Staging</p>
		</x-sidebars.sidebar-link>
		<x-sidebars.sidebar-link href="/terminations" active="{{ request()->routeIs('terminations.*') }}">
			<i class="bi bi-arrow-return-right nav-icon"></i>
			<p>Termination</p>
		</x-sidebars.sidebar-link>
		<x-sidebars.sidebar-link href="/claims" active="{{ request()->routeIs('claims.*') }}">
			<i class="bi bi-arrow-return-right nav-icon"></i>
			<p>Claim</p>
		</x-sidebars.sidebar-link>
@stop
