@extends('layouts.public')

@section('content')
<section class="bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-14 md:py-20">
        <h1 class="text-4xl md:text-5xl gc-heading">Genchess Official Chess Supplies</h1>
        <p class="mt-4 text-slate-300 max-w-2xl">
            Chessboards, clocks, books, apparel, and school packages for schools, communities, organizations, and individual buyers.
        </p>
    </div>
</section>

<section id="bulk-order" class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <h2 class="text-2xl gc-heading mb-5">Categories</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @forelse($categories as $category)
                <a href="{{ route('store.category', $category) }}" class="gc-panel p-4 hover:border-brand-500 transition">
                    <img src="{{ $category->image ?: '/images/products/placeholder-board.jpg' }}" alt="{{ $category->title }}" class="w-full h-32 object-cover rounded">
                    <h3 class="mt-3 font-semibold text-slate-900">{{ $category->title }}</h3>
                </a>
            @empty
                <p class="text-slate-600">No categories yet.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <h2 class="text-2xl gc-heading mb-5">Featured Products</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($featuredProducts as $product)
                <article class="gc-panel p-4">
                    <img src="{{ $product->image_placeholder ?: '/images/products/placeholder-board.jpg' }}" alt="{{ $product->name }}" class="w-full h-40 object-cover rounded">
                    <h3 class="mt-3 font-semibold text-slate-900">{{ $product->name }}</h3>
                    <p class="text-slate-700 text-sm mt-1">NGN {{ number_format($product->price_kobo / 100, 2) }}</p>
                    @if($product->stock_quantity < 1)
                        <span class="inline-block mt-2 text-xs rounded bg-red-100 text-red-700 px-2 py-1">Out of Stock</span>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('store.product', $product) }}" class="gc-btn-primary">View Product</a>
                    </div>
                </article>
            @empty
                <p class="text-slate-600">No featured products yet.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
        <h2 class="text-2xl gc-heading mb-4">Request Bulk Order Quote</h2>
        <form method="POST" action="{{ route('store.bulk-order.store') }}" enctype="multipart/form-data" class="gc-panel p-5 space-y-4">
            @csrf
            <div class="grid gap-3 md:grid-cols-2">
                <input name="organization_name" class="border rounded px-3 py-2 w-full" placeholder="Organization name" required>
                <input name="contact_person" class="border rounded px-3 py-2 w-full" placeholder="Contact person" required>
                <input name="phone" class="border rounded px-3 py-2 w-full" placeholder="Phone" required>
                <input type="email" name="email" class="border rounded px-3 py-2 w-full" placeholder="Email" required>
                <input name="delivery_location" class="border rounded px-3 py-2 w-full md:col-span-2" placeholder="Delivery location" required>
            </div>
            <textarea name="products_needed" rows="3" class="border rounded px-3 py-2 w-full" placeholder="Products needed (e.g. 10 boards, 10 clocks, 5 books)" required></textarea>
            <div class="grid gap-3 md:grid-cols-2">
                <input type="number" name="quantity" min="1" class="border rounded px-3 py-2 w-full" placeholder="Quantity" required>
                <input type="file" name="custom_logo" class="border rounded px-3 py-2 w-full">
            </div>
            <textarea name="additional_notes" rows="2" class="border rounded px-3 py-2 w-full" placeholder="Additional notes"></textarea>
            <button class="gc-btn-primary" type="submit">Submit Bulk Quote Request</button>
        </form>
    </div>
</section>
@endsection
