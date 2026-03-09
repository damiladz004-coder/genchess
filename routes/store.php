<?php

use App\Http\Controllers\Public\StoreController;
use Illuminate\Support\Facades\Route;

Route::domain('store.genchess.ng')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('portal.store.home');
    });
