<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'classteacher'])->prefix('class-teacher')->name('class-teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ClassTeacher\DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/timetable', [\App\Http\Controllers\ClassTeacher\TimetableController::class, 'index'])
        ->name('timetable.index');
    Route::get('/feedback', [\App\Http\Controllers\ClassTeacher\FeedbackController::class, 'create'])
        ->name('feedback.create');
    Route::post('/feedback', [\App\Http\Controllers\ClassTeacher\FeedbackController::class, 'store'])
        ->name('feedback.store');
});
