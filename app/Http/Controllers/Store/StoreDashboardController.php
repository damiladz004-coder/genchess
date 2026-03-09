<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Public\StoreController as PublicStoreController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class StoreDashboardController extends Controller
{
    public function index(PublicStoreController $storeController): View|RedirectResponse
    {
        return $storeController->index();
    }
}
