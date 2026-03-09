<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\InstructorMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\SchoolAdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')->group(base_path('routes/admin.php'));
            Route::middleware('web')->group(base_path('routes/school.php'));
            Route::middleware('web')->group(base_path('routes/training.php'));
            Route::middleware('web')->group(base_path('routes/instructor.php'));
            Route::middleware('web')->group(base_path('routes/store.php'));
        },
    )
    ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'instructor' => \App\Http\Middleware\InstructorMiddleware::class,
            'schooladmin' => \App\Http\Middleware\SchoolAdminMiddleware::class,
            'superadmin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'classteacher' => \App\Http\Middleware\ClassTeacherMiddleware::class,
            'trainingportal' => \App\Http\Middleware\TrainingPortalMiddleware::class,
            'training.paid' => \App\Http\Middleware\EnsurePaidTrainingEnrollment::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));

        $middleware->validateCsrfTokens(except: [
            'payments/paystack/webhook',
            'payments/store/paystack/webhook',
        ]);

        $middleware->appendToGroup('web', \App\Http\Middleware\ForcePasswordChange::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
