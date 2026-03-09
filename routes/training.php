<?php

use App\Http\Controllers\Instructor\TrainingController;
use Illuminate\Support\Facades\Route;

Route::domain('training.genchess.ng')
    ->middleware(['auth', 'verified', 'trainingportal'])
    ->group(function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', [TrainingController::class, 'index']);
    });
