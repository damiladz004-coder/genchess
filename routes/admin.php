<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::domain('admin.genchess.ng')
    ->middleware(['auth', 'verified', 'superadmin'])
    ->group(function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });
