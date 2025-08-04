<ul class="nav sidebar-menu flex-column"
	data-lte-toggle="treeview"
	data-accordion="false"
	role="menu">
	@include('components.sidebars.sidebar-menu-item', [
		'title' => 'Dashboard',
		'link' => 'dashboard',
		'icon' => 'bi-house-door',
	])

	@include('components.sidebars.sidebar-menu-header', ['title' => 'Assets Management'])

	@include('components.sidebars.sidebar-menu-item', [
		'title' => 'Inventory',
		'link' => 'inventory',
		'icon' => 'bi-boxes',
		'active' => request()->routeIs('inventory.*'),
	])

	@include('components.sidebars.sidebar-menu-item-with-submenu', [
		'title' => 'Operations',
		'icon' => 'bi-speedometer2',
		'submenu' => [
			['title' => 'Staging', 'link' => 'staging', 'active' => request()->routeIs('staging.*')],
			['title' => 'Delivery', 'link' => 'delivery', 'active' => request()->routeIs('delivery.*')],
			['title' => 'Deployment', 'link' => 'deployment', 'active' => request()->routeIs('deployment.*')],
			['title' => 'Request Upgrade', 'link' => 'upgrade', 'active' => request()->routeIs('upgrade.*')],
			['title' => 'Termination', 'link' => 'termination', 'active' => request()->routeIs('termination.*')],
		],
	])
</ul>
