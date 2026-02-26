<?php

namespace App\Http\Middleware;

use App\Models\TrainingEnrollment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePaidTrainingEnrollment
{
    public function handle(Request $request, Closure $next): Response
    {
        $enrollment = $request->route('enrollment');

        if ($enrollment instanceof TrainingEnrollment) {
            if ($enrollment->user_id !== $request->user()?->id || !$enrollment->isPaid()) {
                return redirect()
                    ->route('training.preview')
                    ->with('error', 'Complete payment to access this course content.');
            }
        }

        return $next($request);
    }
}

