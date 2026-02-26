@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <h1 class="text-3xl gc-heading">{{ $category->title }}</h1>
        @if($category->description)
            <p class="mt-2 text-slate-600">{{ $category->description }}</p>
        @endif

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($products as $product)
                <article class="gc-panel p-4">
                    <img src="{{ $product->image_placeholder ?: '/images/products/placeholder-board.jpg' }}" class="w-full h-40 object-cover rounded" alt="{{ $product->name }}">
                    <h3 class="mt-3 font-semibold">{{ $product->name }}</h3>
                    <p class="text-sm text-slate-700 mt-1">NGN {{ number_format($product->price_kobo / 100, 2) }}</p>
                    <a href="{{ route('store.product', $product) }}" class="gc-btn-secondary mt-3">Open</a>
                </article>
            @empty
                <p class="text-slate-600">No products in this category yet.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $products->links() }}</div>
    </div>
</section>
@endsection

