<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-3xl gc-heading">Store Products</h1>
            <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
        </div>

        <div class="gc-panel p-4">
            <h2 class="text-lg font-semibold mb-3">Create Product</h2>
            <p class="text-sm text-slate-600 mb-3">After creating a product, use "Manage Images" to upload real photos and set a primary image.</p>
            <form method="POST" action="{{ route('admin.store.products.store') }}" class="grid md:grid-cols-3 gap-3">
                @csrf
                <select name="category_id" required>
                    <option value="">Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
                <input name="name" placeholder="Product name" required>
                <input value="SKU auto-generated on save" disabled>
                <input name="price" type="number" step="0.01" placeholder="Price NGN" required>
                <input name="bulk_price" type="number" step="0.01" placeholder="Bulk price NGN">
                <input name="stock_quantity" type="number" placeholder="Stock qty" required>
                <select name="product_type">
                    <option value="standard">standard</option>
                    <option value="school_package">school package</option>
                </select>
                <select name="status">
                    <option value="active">active</option>
                    <option value="inactive">inactive</option>
                </select>
                <label><input type="checkbox" name="featured" value="1"> Featured</label>
                <label><input type="checkbox" name="allow_quote" value="1"> Allow quote</label>
                <label><input type="checkbox" name="has_size_options" value="1"> Size options</label>
                <label><input type="checkbox" name="has_color_options" value="1"> Color options</label>
                <textarea name="description" rows="2" class="md:col-span-3" placeholder="Description"></textarea>
                <button class="gc-btn-primary" type="submit">Save Product</button>
            </form>
        </div>

        <div class="gc-panel p-4 overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            {{ $product->name }}
                            @if($product->featured)
                                <span class="ml-1 inline-block text-[10px] uppercase tracking-wide bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded">Featured</span>
                            @endif
                            <br><span class="text-xs text-slate-500">{{ $product->sku }}</span>
                        </td>
                        <td>{{ $product->category->title ?? '-' }}</td>
                        <td>NGN {{ number_format($product->price_kobo / 100, 2) }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td>{{ $product->status }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.store.products.update', $product) }}" class="grid md:grid-cols-2 gap-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="category_id" value="{{ $product->category_id }}">
                                <input name="name" type="text" value="{{ $product->name }}" class="md:col-span-2" required>
                                <input name="sku" type="text" value="{{ $product->sku }}" required>
                                <input name="price" type="number" step="0.01" value="{{ $product->price_kobo / 100 }}" required>
                                <input type="hidden" name="bulk_price" value="{{ $product->bulk_price_kobo ? $product->bulk_price_kobo / 100 : '' }}">
                                <input type="hidden" name="product_type" value="{{ $product->product_type }}">
                                <input type="hidden" name="description" value="{{ $product->description }}">
                                <input name="stock_quantity" type="number" value="{{ $product->stock_quantity }}">
                                <select name="status">
                                    <option value="active" @selected($product->status==='active')>active</option>
                                    <option value="inactive" @selected($product->status==='inactive')>inactive</option>
                                </select>
                                <button class="gc-btn-secondary text-xs px-3 py-1.5 md:col-span-2">Update</button>
                            </form>
                            <form method="POST" action="{{ route('admin.store.products.featured', $product) }}" class="mt-1">
                                @csrf
                                @method('PATCH')
                                <button class="gc-btn-secondary text-xs px-3 py-1.5" type="submit">
                                    {{ $product->featured ? 'Unfeature' : 'Feature' }}
                                </button>
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


