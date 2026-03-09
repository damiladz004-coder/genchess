<?php

use Illuminate\Support\Facades\Route;

Route::domain('training.genchess.ng')
    ->middleware(['auth', 'verified', 'trainingportal'])
    ->group(function () {
        Route::redirect('/', '/dashboard');
        Route::view('/dashboard', 'training.index');
    });
