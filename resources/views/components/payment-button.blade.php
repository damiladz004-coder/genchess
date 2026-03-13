@props([
    'amount',
    'email',
    'purpose',
    'metadata' => [],
    'label' => 'Pay Now',
])

<form method="POST" action="{{ route('payments.initialize') }}" {{ $attributes->except(['class']) }}>
    @csrf
    <input type="hidden" name="amount" value="{{ $amount }}">
    <input type="hidden" name="email" value="{{ $email }}">
    <input type="hidden" name="purpose" value="{{ $purpose }}">

    @foreach($metadata as $key => $value)
        <input type="hidden" name="metadata[{{ $key }}]" value="{{ $value }}">
    @endforeach

    <button type="submit" class="{{ $attributes->get('class', 'gc-btn-primary') }}">
        {{ $label }}
    </button>
</form>
