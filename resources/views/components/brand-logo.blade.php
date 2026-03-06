@props([
    'class' => 'h-10 w-auto',
    'alt' => 'Genchess logo',
    'src' => 'images/logo/genchess-logo-brick.png',
])

<span {{ $attributes->merge(['class' => 'brand-logo inline-flex items-center']) }}>
    <img src="{{ asset($src) }}" alt="{{ $alt }}" class="{{ $class }}">
</span>
