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
                <x-nigeria-state-select name="state" id="checkout-state" :value="old('state')" class="border rounded px-3 py-2 w-full" required />
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

        <div class="gc-panel p-5 h-fit" id="order-summary" data-subtotal-kobo="{{ (int) $cart['subtotal_kobo'] }}">
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
                <span id="checkout-subtotal">NGN {{ number_format($cart['subtotal_kobo'] / 100, 2) }}</span>
            </div>
            <div class="flex justify-between mt-2">
                <span>Delivery Fee</span>
                <span id="checkout-delivery-fee">NGN 0.00</span>
            </div>
            <p id="checkout-delivery-note" class="text-xs text-slate-600 mt-1">Select state to see delivery fee.</p>
            <div class="flex justify-between mt-2 font-semibold">
                <span>Total</span>
                <span id="checkout-total">NGN {{ number_format($cart['subtotal_kobo'] / 100, 2) }}</span>
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        const stateInput = document.getElementById('checkout-state');
        const summary = document.getElementById('order-summary');
        const deliveryFeeEl = document.getElementById('checkout-delivery-fee');
        const totalEl = document.getElementById('checkout-total');
        const noteEl = document.getElementById('checkout-delivery-note');

        if (!stateInput || !summary || !deliveryFeeEl || !totalEl || !noteEl) {
            return;
        }

        const subtotalKobo = Number(summary.dataset.subtotalKobo || 0);

        const formatNgn = (amountNaira) => `NGN ${Number(amountNaira).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

        const updateDeliverySummary = () => {
            const normalized = (stateInput.value || '').trim().toLowerCase();

            if (!normalized) {
                deliveryFeeEl.textContent = formatNgn(0);
                totalEl.textContent = formatNgn(subtotalKobo / 100);
                noteEl.textContent = 'Select state to see delivery fee.';
                return;
            }

            const deliveryFee = normalized === 'lagos' ? 1500 : 3500;
            const totalNaira = (subtotalKobo / 100) + deliveryFee;

            deliveryFeeEl.textContent = formatNgn(deliveryFee);
            totalEl.textContent = formatNgn(totalNaira);
            noteEl.textContent = deliveryFee === 1500
                ? 'Delivery Fee: NGN 1500 (Within Lagos)'
                : 'Delivery Fee: NGN 3500 (Outside Lagos)';
        };

        stateInput.addEventListener('change', updateDeliverySummary);
        stateInput.addEventListener('input', updateDeliverySummary);
        updateDeliverySummary();
    })();
</script>
@endsection
