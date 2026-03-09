<?php

use App\Http\Controllers\Training\TrainingDashboardController;
use Illuminate\Support\Facades\Route;

Route::domain('training.genchess.ng')
    ->middleware(['auth', 'verified', 'trainingportal'])
    ->group(function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', [TrainingDashboardController::class, 'index']);
    });
