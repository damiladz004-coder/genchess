<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest('id')->paginate(30);
        $categories = Category::where('status', 'active')->orderBy('title')->get();

        return view('admin.store.products', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);

        $slug = Str::slug($data['name']);
        $base = $slug;
        $n = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        $sku = $this->generateUniqueSku($data['name']);

        Product::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'price_kobo' => (int) round(((float) $data['price']) * 100),
            'bulk_price_kobo' => isset($data['bulk_price']) ? (int) round(((float) $data['bulk_price']) * 100) : null,
            'sku' => $sku,
            'stock_quantity' => (int) $data['stock_quantity'],
            'image_placeholder' => null,
            'product_type' => $data['product_type'],
            'featured' => $request->boolean('featured'),
            'allow_quote' => $request->boolean('allow_quote'),
            'has_size_options' => $request->boolean('has_size_options'),
            'has_color_options' => $request->boolean('has_color_options'),
            'status' => $data['status'],
        ]);

        return back()->with('success', "Product created. SKU: {$sku}");
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);
        $beforeStock = (int) $product->stock_quantity;
        $newStock = (int) $data['stock_quantity'];

        $product->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price_kobo' => (int) round(((float) $data['price']) * 100),
            'bulk_price_kobo' => isset($data['bulk_price']) ? (int) round(((float) $data['bulk_price']) * 100) : null,
            'sku' => strtoupper(trim($data['sku'])),
            'stock_quantity' => (int) $data['stock_quantity'],
            'image_placeholder' => $product->image_placeholder,
            'product_type' => $data['product_type'],
            'featured' => $request->has('featured') ? $request->boolean('featured') : $product->featured,
            'allow_quote' => $request->has('allow_quote') ? $request->boolean('allow_quote') : $product->allow_quote,
            'has_size_options' => $request->has('has_size_options') ? $request->boolean('has_size_options') : $product->has_size_options,
            'has_color_options' => $request->has('has_color_options') ? $request->boolean('has_color_options') : $product->has_color_options,
            'status' => $data['status'],
        ]);

        if ($beforeStock !== $newStock) {
            InventoryLog::create([
                'product_id' => $product->id,
                'order_id' => null,
                'created_by' => auth()->id(),
                'action' => 'adjust',
                'quantity' => abs($newStock - $beforeStock),
                'before_stock' => $beforeStock,
                'after_stock' => $newStock,
                'note' => 'Manual stock adjustment from admin product update.',
            ]);
        }

        return back()->with('success', 'Product updated.');
    }

    public function toggleFeatured(Product $product)
    {
        $product->update([
            'featured' => !$product->featured,
        ]);

        return back()->with('success', $product->featured ? 'Product featured.' : 'Product unfeatured.');
    }

    public function images(Product $product)
    {
        $product->load('images');

        return view('admin.store.product-images', compact('product'));
    }

    public function storeImage(Request $request, Product $product)
    {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_primary' => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('store/product-images', 'public');
        $publicPath = '/storage/' . ltrim($path, '/');

        $isPrimary = $request->boolean('is_primary');
        if (!$isPrimary && !$product->images()->where('is_primary', true)->exists()) {
            $isPrimary = true;
        }

        if ($isPrimary) {
            $product->images()->update(['is_primary' => false]);
        }

        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $publicPath,
            'is_primary' => $isPrimary,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);

        if ($isPrimary) {
            $product->update(['image_placeholder' => $publicPath]);
        }

        return back()->with('success', 'Product image uploaded.');
    }

    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        abort_unless($image->product_id === $product->id, 404);

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        $product->update(['image_placeholder' => $image->image_path]);

        return back()->with('success', 'Primary image updated.');
    }

    public function destroyImage(Product $product, ProductImage $image)
    {
        abort_unless($image->product_id === $product->id, 404);

        if (str_starts_with($image->image_path, '/storage/')) {
            $storagePath = ltrim(str_replace('/storage/', '', $image->image_path), '/');
            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }
        }

        $wasPrimary = (bool) $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $newPrimary = $product->images()->orderBy('sort_order')->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
                $product->update(['image_placeholder' => $newPrimary->image_path]);
            }
        }

        return back()->with('success', 'Product image removed.');
    }

    protected function validateProduct(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'bulk_price' => 'nullable|numeric|min:0',
            'sku' => [Rule::requiredIf($productId !== null), 'nullable', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($productId)],
            'stock_quantity' => 'required|integer|min:0',
            'product_type' => 'required|in:standard,school_package',
            'status' => 'required|in:active,inactive',
        ]);
    }

    protected function generateUniqueSku(string $name): string
    {
        $prefix = strtoupper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
        $prefix = str_pad($prefix ?: 'PRD', 3, 'X');

        do {
            $sku = sprintf('%s-%s', $prefix, strtoupper(Str::random(6)));
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }
}
