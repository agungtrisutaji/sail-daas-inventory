<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon bi {{ $icon }}"></i>
        <p>
            {{ $title }}
            <i class="nav-arrow bi bi-chevron-right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @foreach($submenu as $item)
            @if(isset($item['submenu']))
                @include('components.sidebars.sidebar-menu-item-multilevel', $item)
            @else
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi {{ $item['icon'] ?? 'bi-circle' }}"></i>
                        <p>{{ $item['title'] }}</p>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</li>
