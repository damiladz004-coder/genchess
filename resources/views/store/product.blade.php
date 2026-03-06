@extends('layouts.public')

@section('content')
@php
    $defaultImage = $product->image_placeholder ?: '/images/products/placeholder-board.jpg';
    $galleryImages = $product->images->pluck('image_path')->filter()->values();

    if ($galleryImages->isEmpty()) {
        $galleryImages = collect([$defaultImage]);
    } elseif ($product->image_placeholder && !$galleryImages->contains($product->image_placeholder)) {
        $galleryImages->prepend($product->image_placeholder);
    }

    $initialIndex = $galleryImages->search($product->image_placeholder ?: $defaultImage);
    $initialIndex = $initialIndex === false ? 0 : $initialIndex;
@endphp
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 grid gap-8 lg:grid-cols-2">
        <div
            x-data="{
                images: @js($galleryImages->values()),
                activeIndex: {{ $initialIndex }},
                prev() {
                    this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
                },
                next() {
                    this.activeIndex = (this.activeIndex + 1) % this.images.length;
                },
                go(index) {
                    this.activeIndex = index;
                }
            }"
            x-on:keydown.left.prevent="prev()"
            x-on:keydown.right.prevent="next()"
            tabindex="0"
            class="outline-none"
        >
            <img :src="images[activeIndex]" alt="{{ $product->name }}" class="w-full h-96 object-cover rounded-xl border">

            <div class="flex items-center gap-2 mt-3">
                <button type="button" class="gc-btn-secondary text-xs px-3 py-1.5" x-on:click="prev()" x-bind:disabled="images.length < 2">Prev</button>
                <div class="flex-1 overflow-x-auto">
                    <div class="flex gap-2 pb-1 min-w-max">
                        <template x-for="(image, index) in images" :key="`${image}-${index}`">
                            <button
                                type="button"
                                class="h-20 w-20 rounded border overflow-hidden shrink-0"
                                x-bind:class="activeIndex === index ? 'ring-2 ring-slate-700 border-slate-700' : 'border-slate-200'"
                                x-on:click="go(index)"
                            >
                                <img :src="image" alt="{{ $product->name }}" class="h-full w-full object-cover">
                            </button>
                        </template>
                    </div>
                </div>
                <button type="button" class="gc-btn-secondary text-xs px-3 py-1.5" x-on:click="next()" x-bind:disabled="images.length < 2">Next</button>
            </div>
        </div>
        <div>
            <h1 class="text-3xl gc-heading">{{ $product->name }}</h1>
            <p class="mt-2 text-lg font-semibold text-slate-900">NGN {{ number_format($product->price_kobo / 100, 2) }}</p>
            <p class="text-sm mt-1 {{ $product->isOutOfStock() ? 'text-red-600' : 'text-emerald-700' }}">
                {{ $product->isOutOfStock() ? 'Out of Stock' : 'In Stock: '.$product->stock_quantity }}
            </p>

            <form method="POST" action="{{ route('store.cart.add', $product) }}" enctype="multipart/form-data" class="mt-5 space-y-3">
                @csrf
                <div>
                    <label class="text-sm text-slate-600">Quantity</label>
                    <input type="number" name="quantity" min="1" max="{{ max(1, $product->stock_quantity) }}" value="1" class="border rounded px-3 py-2 w-full">
                </div>

                @if($product->has_size_options)
                    <div>
                        <label class="text-sm text-slate-600">Size</label>
                        <select name="size" class="border rounded px-3 py-2 w-full">
                            <option value="">Select size</option>
                            <option>S</option><option>M</option><option>L</option><option>XL</option>
                        </select>
                    </div>
                @endif

                @if($product->has_color_options)
                    <div>
                        <label class="text-sm text-slate-600">Color</label>
                        <input name="color" class="border rounded px-3 py-2 w-full" placeholder="e.g. Black">
                    </div>
                @endif

                @if($product->has_size_options || $product->has_color_options || $product->allow_quote)
                    <div>
                        <label class="text-sm text-slate-600">Custom logo (optional)</label>
                        <input type="file" name="custom_logo" class="border rounded px-3 py-2 w-full">
                    </div>
                @endif

                <div class="flex gap-3">
                    <button type="submit" class="gc-btn-primary" @disabled($product->isOutOfStock())>Add to Cart</button>
                    @if($product->allow_quote || $product->product_type === 'school_package')
                        <a href="{{ route('store.index') }}#bulk-order" class="gc-btn-secondary">Request Quote</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>

<section class="bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <h2 class="text-2xl gc-heading">Description</h2>
        <p class="mt-3 text-slate-700 whitespace-pre-line">{{ $product->description ?: 'No description yet.' }}</p>

        @if($relatedProducts->isNotEmpty())
            <h3 class="text-xl gc-heading mt-10 mb-4">Related Products</h3>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($relatedProducts as $related)
                    <a href="{{ route('store.product', $related) }}" class="gc-panel p-3">
                        <img src="{{ $related->image_placeholder ?: '/images/products/placeholder-board.jpg' }}" class="w-full h-28 object-cover rounded" alt="{{ $related->name }}">
                        <div class="mt-2 text-sm font-semibold">{{ $related->name }}</div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
