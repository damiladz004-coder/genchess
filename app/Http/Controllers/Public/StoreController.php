<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class StoreController extends Controller
{
    public function index()
    {
        return $this->unavailableResponse();
    }

    public function category(Category $category)
    {
        return $this->unavailableResponse();
    }

    public function product(Product $product)
    {
        return $this->unavailableResponse();
    }

    private function unavailableResponse()
    {
        return response()
            ->view('store.unavailable')
            ->setStatusCode(503);
    }
}
