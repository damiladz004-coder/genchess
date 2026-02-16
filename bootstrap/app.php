<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\InstructorMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\SchoolAdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'instructor' => \App\Http\Middleware\InstructorMiddleware::class,
            'schooladmin' => \App\Http\Middleware\SchoolAdminMiddleware::class,
            'superadmin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'classteacher' => \App\Http\Middleware\ClassTeacherMiddleware::class,
        ]);

        $middleware->appendToGroup('web', \App\Http\Middleware\ForcePasswordChange::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
