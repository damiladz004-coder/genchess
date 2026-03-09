<?php

use App\Http\Controllers\Instructor\DashboardController;
use Illuminate\Support\Facades\Route;

Route::domain('instructor.genchess.ng')
    ->middleware(['auth', 'verified', 'instructor'])
    ->group(function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    });
