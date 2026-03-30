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
        return $this->storeUnavailable();
    }

    public function add(Request $request, Product $product, StoreCartService $cartService)
    {
        return redirect()->route('store.index')
            ->withErrors(['store' => 'The chess store is temporarily unavailable while product images are being uploaded.']);
    }

    public function update(Request $request, StoreCartService $cartService, string $lineKey)
    {
        return redirect()->route('store.index')
            ->withErrors(['store' => 'The chess store is temporarily unavailable while product images are being uploaded.']);
    }

    public function remove(StoreCartService $cartService, string $lineKey)
    {
        return redirect()->route('store.index')
            ->withErrors(['store' => 'The chess store is temporarily unavailable while product images are being uploaded.']);
    }

    private function storeUnavailable()
    {
        return response()
            ->view('store.unavailable')
            ->setStatusCode(503);
    }
}
