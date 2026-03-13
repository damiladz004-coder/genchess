<?php

namespace App\Http\Middleware;

use App\Models\Payment;
use App\Models\TrainingEnrollment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTrainingPayment
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $enrollment = $request->route('enrollment');

        if (!$user) {
            return redirect()->route('login');
        }

        if ($enrollment instanceof TrainingEnrollment && (int) $enrollment->user_id !== (int) $user->id) {
            abort(403);
        }

        $hasPaidTrainingPayment = Payment::query()
            ->where('user_id', $user->id)
            ->where('purpose', Payment::PURPOSE_TRAINING)
            ->where('status', 'paid')
            ->exists();

        if ($enrollment instanceof TrainingEnrollment && $enrollment->isPaid()) {
            return $next($request);
        }

        if (!$hasPaidTrainingPayment) {
            return redirect()
                ->route('training.checkout')
                ->with('error', 'Complete training payment to access this content.');
        }

        return $next($request);
    }
}

