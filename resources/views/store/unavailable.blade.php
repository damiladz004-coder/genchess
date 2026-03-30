@extends('layouts.public')

@section('content')
<section class="bg-slate-900 text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-24 grid gap-8 lg:grid-cols-[1.05fr_0.95fr] items-center">
        <div>
            <h1 class="text-4xl md:text-5xl gc-heading">Chess Store Temporarily Unavailable</h1>
            <p class="mt-4 max-w-2xl text-slate-300">
                We are still uploading product images and organizing the store pages. The chess store will be reopened as soon as the product gallery is ready.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('home') }}" class="gc-btn-primary">Back to Home</a>
                <a href="{{ route('contact') }}" class="gc-btn-secondary">Contact Us</a>
            </div>
        </div>
        <div>
            <img
                src="{{ asset('images/chess%20products/chessproducts.jpg') }}"
                alt="Genchess chess products"
                class="w-full h-72 md:h-96 rounded-2xl border border-slate-700 object-cover shadow-soft"
            >
        </div>
    </div>
</section>
@endsection
