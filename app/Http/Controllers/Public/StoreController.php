<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class StoreController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('categories') || !Schema::hasTable('products')) {
            return view('store.index', [
                'categories' => collect(),
                'featuredProducts' => collect(),
            ])->with('error', 'Store is being set up. Please run migrations and seeders.');
        }

        $categories = Category::where('status', 'active')->orderBy('title')->get();
        $featuredProducts = Product::with('category')
            ->where('status', 'active')
            ->where('featured', true)
            ->latest('id')
            ->take(12)
            ->get();

        return view('store.index', compact('categories', 'featuredProducts'));
    }

    public function category(Category $category)
    {
        abort_unless($category->status === 'active', 404);

        $products = Product::where('category_id', $category->id)
            ->where('status', 'active')
            ->latest('id')
            ->paginate(12);

        return view('store.category', compact('category', 'products'));
    }

    public function product(Product $product)
    {
        abort_unless($product->status === 'active', 404);
        $product->load('category', 'images');

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('status', 'active')
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('store.product', compact('product', 'relatedProducts'));
    }
}
