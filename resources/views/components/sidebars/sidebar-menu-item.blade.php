@props(['active' => false, 'title', 'link' => '#', 'icon'])

@php
$classes = $active ?? false ? 'nav-link active' : 'nav-link';
@endphp

<li class="nav-item">
    <a href="{{ route($link ?? '#') }}" {{ $attributes->merge(['class' => $classes]) }} class="nav-link">
        <i class="nav-icon bi {{ $icon }}"></i>
        <p>{{ $title }}</p>
    </a>
</li>
