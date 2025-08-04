<li class="nav-item">
	<a href="#" class="nav-link {{ request()->routeIs(['deliveries.*', 'stagings.*', 'terminations.*', 'claims.*']) ? 'active' : '' }}">
		<i class="nav-icon bi bi-speedometer"></i>
			@yield('treeview-title')
	</a>
	<ul class="nav nav-treeview">
        @yield('treeview-items')
	</ul>
</li>
