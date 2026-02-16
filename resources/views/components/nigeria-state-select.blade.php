@props([
    'name' => 'state',
    'value' => '',
    'required' => false,
    'id' => null,
    'placeholder' => 'Select State',
])

@php
    $states = config('nigeria.states', []);

    $selectedValue = old($name, $value);
    $selectId = $id ?? $name;
@endphp

<select
    id="{{ $selectId }}"
    name="{{ $name }}"
    {{ $required ? 'required' : '' }}
    {{ $attributes->merge(['class' => 'w-full border rounded-lg px-4 py-2']) }}
>
    <option value="">{{ $placeholder }}</option>
    @foreach($states as $state)
        <option value="{{ $state }}" @selected($selectedValue === $state)>{{ $state }}</option>
    @endforeach
</select>
