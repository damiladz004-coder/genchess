@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-16">
        <div class="gc-panel p-8 text-center">
            <h1 class="text-3xl gc-heading">Order Received</h1>
            <p class="mt-3 text-slate-700">Order Number: <strong>{{ $order->order_number }}</strong></p>
            <p class="mt-1 text-slate-700">Payment: <strong>{{ ucfirst($order->payment_status) }}</strong></p>
            <p class="mt-1 text-slate-700">Status: <strong>{{ ucfirst($order->status) }}</strong></p>
            <p class="mt-1 text-slate-700">Total: <strong>NGN {{ number_format($order->total_kobo / 100, 2) }}</strong></p>

            <div class="mt-6 flex justify-center gap-3">
                <a href="{{ route('store.index') }}" class="gc-btn-secondary">Continue Shopping</a>
                <a href="{{ route('home') }}" class="gc-btn-primary">Back Home</a>
            </div>
        </div>
    </div>
</section>
@endsection

