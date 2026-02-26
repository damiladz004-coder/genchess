<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\StoreCartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(StoreCartService $cartService)
    {
        $cart = $cartService->summary();

        return view('store.cart', compact('cart'));
    }

    public function add(Request $request, Product $product, StoreCartService $cartService)
    {
        abort_unless($product->status === 'active', 422, 'Product is inactive.');

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:30',
            'custom_logo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($product->stock_quantity < (int) $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Requested quantity exceeds available stock.']);
        }

        $logoPath = null;
        if ($request->hasFile('custom_logo')) {
            $logoPath = $request->file('custom_logo')->store('store/custom-logos', 'public');
        }

        $cartService->add($product, (int) $validated['quantity'], [
            'size' => $validated['size'] ?? null,
            'color' => $validated['color'] ?? null,
            'logo_path' => $logoPath,
        ]);

        return redirect()->route('store.cart')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, StoreCartService $cartService, string $lineKey)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0|max:100',
        ]);

        $cartService->update($lineKey, (int) $validated['quantity']);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(StoreCartService $cartService, string $lineKey)
    {
        $cartService->remove($lineKey);

        return back()->with('success', 'Item removed.');
    }
}

