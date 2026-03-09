<?php

use App\Http\Controllers\Public\StoreController;
use Illuminate\Support\Facades\Route;

Route::domain('store.genchess.ng')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', [StoreController::class, 'index'])->name('portal.store.dashboard');
    });
});
