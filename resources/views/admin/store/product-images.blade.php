<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-3xl gc-heading">Product Images</h1>
                <p class="text-sm text-slate-600">{{ $product->name }} ({{ $product->sku }})</p>
            </div>
            <a href="{{ route('admin.store.products.index') }}" class="gc-btn-secondary">Back to Products</a>
        </div>

        <div class="gc-panel p-4">
            <h2 class="text-lg font-semibold mb-3">Upload Image</h2>
            <p class="text-sm text-slate-600 mb-3">Mark one image as primary. Primary image is used on product cards and detail page.</p>
            <form method="POST" action="{{ route('admin.store.products.images.store', $product) }}" enctype="multipart/form-data" class="grid md:grid-cols-4 gap-3">
                @csrf
                <input type="file" name="image" class="md:col-span-2" required>
                <input type="number" name="sort_order" min="0" value="0" placeholder="Sort order">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_primary" value="1">
                    Set as primary
                </label>
                <button class="gc-btn-primary md:col-span-4 w-fit" type="submit">Upload</button>
            </form>
        </div>

        <div class="gc-panel p-4">
            <h2 class="text-lg font-semibold mb-3">Existing Images</h2>
            @if($product->images->isEmpty())
                <p class="text-slate-600">No images uploaded yet.</p>
            @else
                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach($product->images as $image)
                        <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-3 space-y-2 bg-white dark:bg-slate-900">
                            <img src="{{ $image->image_path }}" alt="{{ $product->name }}" class="w-full h-40 object-cover rounded-lg">
                            <div class="text-xs text-slate-600 break-all">{{ $image->image_path }}</div>
                            <div class="text-xs text-slate-700 dark:text-slate-300">Sort: {{ $image->sort_order }}</div>
                            @if($image->is_primary)
                                <div class="text-xs inline-block bg-emerald-100 text-emerald-700 rounded-full px-2.5 py-1 font-semibold">Primary</div>
                            @else
                                <form method="POST" action="{{ route('admin.store.products.images.primary', [$product, $image]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-brand-700 underline text-xs">Set Primary</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.store.products.images.destroy', [$product, $image]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-rose-700 underline text-xs" onclick="return confirm('Delete this image?')">Delete</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
