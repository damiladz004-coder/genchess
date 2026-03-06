@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block rounded-lg px-3 py-2 text-sm font-semibold bg-brand-700 text-white'
            : 'block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
