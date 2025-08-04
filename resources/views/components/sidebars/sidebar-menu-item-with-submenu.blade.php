@props(['active' => false])

@php
$classes = $active ?? false ? 'nav-link active' : 'nav-link';
@endphp

<li class="nav-item">
    <a href="#" {{ $attributes->merge(['class' => $classes]) }} class="nav-link">
        <i class="nav-icon bi {{ $icon }}"></i>
        <p>
            {{ $title }}
            @isset($badge)
            <span class="nav-badge badge {{ $badge['class'] }}">{{ $badge['text'] }}</span>
            @endisset
            <i class="nav-arrow bi bi-chevron-right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @foreach($submenu as $item)
        @if(isset($item['submenu']))
        @include('components.sidebars.sidebar-menu-item-multilevel', $item)
        @else
        <li class="nav-item">
            <a href="{{ route($item['link']) }}" {{ $attributes->merge(['class' => $classes]) }} class="nav-link">
                <i class="nav-icon bi bi-arrow-return-right"></i>
                <p>{{ $item['title'] }}</p>
            </a>
        </li>
        @endif
        @endforeach
    </ul>
</li>