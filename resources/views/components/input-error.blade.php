@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'small text-danger my-i']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
