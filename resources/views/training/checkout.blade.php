@extends('layouts.app')

@section('content')
@php
    $standard = (int) $course->price_kobo;
    $defaultDiscount = max(0, ((int) $course->price_kobo) - ((int) $course->discount_price_kobo));
@endphp

<div class="space-y-6 max-w-3xl">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Training Checkout</h2>
        <a href="{{ route('training.preview') }}" class="gc-btn-secondary">Back to Preview</a>
    </div>

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="gc-panel p-6">
        <h3 class="text-xl font-semibold text-slate-900">Genchess Certified Chess Instructor Program (GCCIP)</h3>
        <p class="text-slate-600 mt-1">Currency: NGN</p>
        <p class="mt-2 text-sm text-slate-600">Your referral code: <span class="font-semibold">{{ $myReferralCode }}</span></p>

        <form method="POST" action="{{ route('training.checkout.initialize') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div>
                <label class="block text-sm font-medium text-slate-700">Coupon Code</label>
                <input type="text" name="coupon_code" id="coupon_code" value="{{ old('coupon_code', $couponCode) }}"
                    placeholder="Enter early bird / referral coupon"
                    class="mt-1 w-full rounded border-slate-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Referral Code (Optional)</label>
                <input type="text" name="referral_code" value="{{ old('referral_code', $referralCode) }}"
                    placeholder="Friend's referral code"
                    class="mt-1 w-full rounded border-slate-300">
            </div>

            <div class="rounded-lg border border-slate-200 p-4 bg-slate-50 text-sm">
                <div class="flex justify-between">
                    <span>Original</span>
                    <span id="subtotal">₦{{ number_format($standard / 100) }}</span>
                </div>
                <div class="flex justify-between mt-2">
                    <span>Discount</span>
                    <span id="discount">-₦0</span>
                </div>
                <div class="flex justify-between mt-2 font-semibold text-base">
                    <span>Total</span>
                    <span id="total">₦{{ number_format(($standard - $defaultDiscount) / 100) }}</span>
                </div>
            </div>

            <button type="button" id="apply-coupon-btn" class="gc-btn-secondary">Apply Coupon</button>
            <button type="submit" class="gc-btn-primary">Proceed to Paystack</button>
        </form>
    </div>
</div>

<script>
document.getElementById('apply-coupon-btn').addEventListener('click', async function () {
    const coupon = document.getElementById('coupon_code').value;
    const token = document.querySelector('input[name="_token"]').value;
    const response = await fetch("{{ route('training.checkout.apply-coupon') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            course_id: {{ $course->id }},
            coupon_code: coupon
        })
    });

    const result = await response.json();
    const subtotal = (result.subtotal_kobo ?? 0) / 100;
    const discount = (result.discount_kobo ?? 0) / 100;
    const total = (result.total_kobo ?? 0) / 100;

    document.getElementById('subtotal').textContent = '₦' + subtotal.toLocaleString();
    document.getElementById('discount').textContent = '-₦' + discount.toLocaleString();
    document.getElementById('total').textContent = '₦' + total.toLocaleString();
});
</script>
@endsection
