@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-12 grid gap-8 md:grid-cols-2">
        <div>
            <h1 class="text-3xl gc-heading">Checkout</h1>
            <form method="POST" action="{{ route('store.checkout.place') }}" class="mt-6 space-y-3">
                @csrf
                <input name="customer_name" class="border rounded px-3 py-2 w-full" placeholder="Full name" required value="{{ old('customer_name', auth()->user()->name ?? '') }}">
                <input name="phone" class="border rounded px-3 py-2 w-full" placeholder="Phone number" required value="{{ old('phone') }}">
                <input type="email" name="email" class="border rounded px-3 py-2 w-full" placeholder="Email" required value="{{ old('email', auth()->user()->email ?? '') }}">
                <textarea name="delivery_address" class="border rounded px-3 py-2 w-full" rows="3" placeholder="Delivery address" required>{{ old('delivery_address') }}</textarea>
                <input name="state" class="border rounded px-3 py-2 w-full" placeholder="State" required value="{{ old('state') }}">
                <select name="order_type" class="border rounded px-3 py-2 w-full" required>
                    <option value="individual">Individual</option>
                    <option value="school">School</option>
                    <option value="organization">Organization</option>
                </select>
                <select name="payment_method" class="border rounded px-3 py-2 w-full" required>
                    <option value="paystack">Paystack (Online Payment)</option>
                    <option value="bank_transfer">Bank Transfer (Manual Verification)</option>
                </select>
                <textarea name="notes" class="border rounded px-3 py-2 w-full" rows="2" placeholder="Additional notes">{{ old('notes') }}</textarea>
                <button class="gc-btn-primary" type="submit">Place Order</button>
            </form>
        </div>

        <div class="gc-panel p-5 h-fit">
            <h2 class="text-xl font-semibold">Order Summary</h2>
            <div class="mt-3 space-y-2 text-sm">
                @foreach($cart['items'] as $item)
                    <div class="flex justify-between">
                        <span>{{ $item['product']->name }} x {{ $item['quantity'] }}</span>
                        <span>NGN {{ number_format($item['line_total_kobo'] / 100, 2) }}</span>
                    </div>
                @endforeach
            </div>
            <hr class="my-4">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>NGN {{ number_format($cart['subtotal_kobo'] / 100, 2) }}</span>
            </div>
            <div class="flex justify-between mt-2">
                <span>Delivery Fee</span>
                <span>NGN 0.00</span>
            </div>
            <div class="flex justify-between mt-2 font-semibold">
                <span>Total</span>
                <span>NGN {{ number_format($cart['subtotal_kobo'] / 100, 2) }}</span>
            </div>
        </div>
    </div>
</section>
@endsection

