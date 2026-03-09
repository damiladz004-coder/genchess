<?php

use App\Http\Controllers\Store\StoreDashboardController;
use Illuminate\Support\Facades\Route;

Route::domain('store.genchess.ng')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', [StoreDashboardController::class, 'index'])->name('portal.store.dashboard');
    });
});
