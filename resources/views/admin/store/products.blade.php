<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold">Store Products</h1>

        <div class="bg-white border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Create Product</h2>
            <p class="text-sm text-slate-600 mb-3">After creating a product, use "Manage Images" to upload real photos and set a primary image.</p>
            <form method="POST" action="{{ route('admin.store.products.store') }}" class="grid md:grid-cols-3 gap-3">
                @csrf
                <select name="category_id" class="border px-3 py-2" required>
                    <option value="">Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
                <input name="name" class="border px-3 py-2" placeholder="Product name" required>
                <input name="sku" class="border px-3 py-2" placeholder="SKU" required>
                <input name="price" type="number" step="0.01" class="border px-3 py-2" placeholder="Price NGN" required>
                <input name="bulk_price" type="number" step="0.01" class="border px-3 py-2" placeholder="Bulk price NGN">
                <input name="stock_quantity" type="number" class="border px-3 py-2" placeholder="Stock qty" required>
                <select name="product_type" class="border px-3 py-2">
                    <option value="standard">standard</option>
                    <option value="school_package">school package</option>
                </select>
                <select name="status" class="border px-3 py-2">
                    <option value="active">active</option>
                    <option value="inactive">inactive</option>
                </select>
                <label><input type="checkbox" name="featured" value="1"> Featured</label>
                <label><input type="checkbox" name="allow_quote" value="1"> Allow quote</label>
                <label><input type="checkbox" name="has_size_options" value="1"> Size options</label>
                <label><input type="checkbox" name="has_color_options" value="1"> Color options</label>
                <textarea name="description" rows="2" class="border px-3 py-2 md:col-span-3" placeholder="Description"></textarea>
                <button class="bg-blue-600 text-white px-4 py-2 rounded" type="submit">Save Product</button>
            </form>
        </div>

        <div class="bg-white border rounded p-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead><tr><th class="text-left">Name</th><th class="text-left">Category</th><th class="text-left">Price</th><th class="text-left">Stock</th><th class="text-left">Status</th><th class="text-left">Action</th></tr></thead>
                <tbody>
                @foreach($products as $product)
                    <tr class="border-t">
                        <td>{{ $product->name }}<br><span class="text-xs text-slate-500">{{ $product->sku }}</span></td>
                        <td>{{ $product->category->title ?? '-' }}</td>
                        <td>NGN {{ number_format($product->price_kobo / 100, 2) }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td>{{ $product->status }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.store.products.update', $product) }}" class="grid md:grid-cols-2 gap-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="category_id" value="{{ $product->category_id }}">
                                <input type="hidden" name="name" value="{{ $product->name }}">
                                <input type="hidden" name="sku" value="{{ $product->sku }}">
                                <input type="hidden" name="price" value="{{ $product->price_kobo / 100 }}">
                                <input type="hidden" name="bulk_price" value="{{ $product->bulk_price_kobo ? $product->bulk_price_kobo / 100 : '' }}">
                                <input type="hidden" name="product_type" value="{{ $product->product_type }}">
                                <input type="hidden" name="description" value="{{ $product->description }}">
                                <input name="stock_quantity" type="number" value="{{ $product->stock_quantity }}" class="border px-2 py-1">
                                <select name="status" class="border px-2 py-1">
                                    <option value="active" @selected($product->status==='active')>active</option>
                                    <option value="inactive" @selected($product->status==='inactive')>inactive</option>
                                </select>
                                <button class="text-blue-600 underline text-xs md:col-span-2">Update</button>
                            </form>
                            <a href="{{ route('admin.store.products.images', $product) }}" class="text-xs text-slate-700 underline">Manage Images</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $products->links() }}</div>
        </div>
    </div>
</x-app-layout>
