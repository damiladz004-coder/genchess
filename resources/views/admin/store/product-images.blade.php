<x-app-layout>
    <div class="py-6 max-w-6xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Product Images</h1>
                <p class="text-sm text-slate-600">{{ $product->name }} ({{ $product->sku }})</p>
            </div>
            <a href="{{ route('admin.store.products.index') }}" class="text-blue-600 underline">Back to Products</a>
        </div>

        <div class="bg-white border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Upload Image</h2>
            <p class="text-sm text-slate-600 mb-3">Tip: mark one image as primary. Primary image is what the store uses on product cards and detail page.</p>
            <form method="POST" action="{{ route('admin.store.products.images.store', $product) }}" enctype="multipart/form-data" class="grid md:grid-cols-4 gap-3">
                @csrf
                <input type="file" name="image" class="border px-3 py-2 md:col-span-2" required>
                <input type="number" name="sort_order" min="0" value="0" class="border px-3 py-2" placeholder="Sort order">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_primary" value="1">
                    Set as primary
                </label>
                <button class="bg-blue-600 text-white px-4 py-2 rounded md:col-span-4 w-fit" type="submit">Upload</button>
            </form>
        </div>

        <div class="bg-white border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Existing Images</h2>
            @if($product->images->isEmpty())
                <p class="text-slate-600">No images uploaded yet.</p>
            @else
                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach($product->images as $image)
                        <div class="border rounded p-3 space-y-2">
                            <img src="{{ $image->image_path }}" alt="{{ $product->name }}" class="w-full h-40 object-cover rounded">
                            <div class="text-xs text-slate-600 break-all">{{ $image->image_path }}</div>
                            <div class="text-xs text-slate-700">Sort: {{ $image->sort_order }}</div>
                            @if($image->is_primary)
                                <div class="text-xs inline-block bg-emerald-100 text-emerald-700 rounded px-2 py-1">Primary</div>
                            @else
                                <form method="POST" action="{{ route('admin.store.products.images.primary', [$product, $image]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-blue-600 underline text-xs">Set Primary</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.store.products.images.destroy', [$product, $image]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 underline text-xs" onclick="return confirm('Delete this image?')">Delete</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
