<?php

use App\Http\Controllers\School\DashboardController;
use Illuminate\Support\Facades\Route;

Route::domain('school.genchess.ng')
    ->middleware(['auth', 'verified', 'schooladmin'])
    ->group(function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });
