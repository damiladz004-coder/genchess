@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-12">
        <h1 class="text-3xl gc-heading">Your Cart</h1>

        @if($cart['count'] < 1)
            <div class="gc-panel p-5 mt-6 text-slate-600">Your cart is empty.</div>
        @else
            <div class="mt-6 space-y-3">
                @foreach($cart['items'] as $item)
                    <div class="gc-panel p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <div class="font-semibold">{{ $item['product']->name }}</div>
                            <div class="text-sm text-slate-600">NGN {{ number_format($item['unit_price_kobo'] / 100, 2) }}</div>
                            @if(!empty($item['options']))
                                <div class="text-xs text-slate-500 mt-1">Options: {{ json_encode($item['options']) }}</div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('store.cart.update', $item['line_key']) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" min="0" value="{{ $item['quantity'] }}" class="border rounded px-2 py-1 w-20">
                                <button class="gc-btn-secondary" type="submit">Update</button>
                            </form>
                            <form method="POST" action="{{ route('store.cart.remove', $item['line_key']) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 underline text-sm" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="gc-panel p-5 mt-6">
                <div class="flex justify-between text-slate-700">
                    <span>Subtotal</span>
                    <span>NGN {{ number_format($cart['subtotal_kobo'] / 100, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-700 mt-2">
                    <span>Delivery Fee</span>
                    <span>NGN 0.00</span>
                </div>
                <div class="flex justify-between font-semibold mt-2">
                    <span>Total</span>
                    <span>NGN {{ number_format($cart['subtotal_kobo'] / 100, 2) }}</span>
                </div>
                <a href="{{ route('store.checkout') }}" class="gc-btn-primary mt-4">Proceed to Checkout</a>
            </div>
        @endif
    </div>
</section>
@endsection

