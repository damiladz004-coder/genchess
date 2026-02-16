<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClassTeacherMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403);
        }

        if (auth()->user()->role !== 'class_teacher') {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
