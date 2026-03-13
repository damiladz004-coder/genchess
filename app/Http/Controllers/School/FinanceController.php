<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\SchoolPayment;
use App\Models\SchoolPricing;
use App\Models\Student;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FinanceController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        $pricings = SchoolPricing::where('school_id', $schoolId)
            ->orderBy('session', 'desc')
            ->orderBy('term')
            ->get();

        $payments = SchoolPayment::where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->get();

        $latestPricing = SchoolPricing::where('school_id', $schoolId)
            ->orderBy('session', 'desc')
            ->orderBy('term', 'desc')
            ->first();

        $studentCount = Student::where('school_id', $schoolId)->count();

        return view('school.finance.index', compact('pricings', 'payments', 'latestPricing', 'studentCount'));
    }

    public function invoice(SchoolPayment $payment)
    {
        $schoolId = auth()->user()->school_id;
        if ($payment->school_id !== $schoolId) {
            abort(403);
        }

        $settings = \App\Models\Setting::whereIn('key', [
            'organization_name',
            'support_email',
            'support_phone',
            'default_currency',
        ])->get()->keyBy('key');

        return view('school.finance.invoice', [
            'payment' => $payment,
            'settings' => $settings,
        ]);
    }

    public function pay(Request $request, SchoolPayment $payment, PaymentService $paymentService)
    {
        $schoolId = auth()->user()->school_id;
        if ($payment->school_id !== $schoolId) {
            abort(403);
        }

        $outstanding = max(0, (int) $payment->total_due - (int) $payment->amount_paid);
        if ($outstanding <= 0) {
            return redirect()->route('school.finance.index')->with('success', 'This invoice is already fully paid.');
        }

        $amountKobo = $outstanding * 100;
        $email = auth()->user()->email;

        $centralPayment = $paymentService->createPendingPayment(
            $request->user(),
            $email,
            $amountKobo,
            Payment::PURPOSE_SCHOOL,
            [
                'school_id' => $schoolId,
                'school_payment_id' => $payment->id,
                'term' => $payment->term,
                'session' => $payment->session,
            ]
        );

        $response = $paymentService->initialize(
            $centralPayment,
            route('payments.callback', ['reference' => $centralPayment->reference])
        );

        if (!($response['status'] ?? false) || !($response['data']['authorization_url'] ?? null)) {
            $paymentService->markFailed($centralPayment, $response);
            Log::warning('School finance paystack initialize failed.', [
                'school_payment_id' => $payment->id,
                'payment_id' => $centralPayment->id,
                'response' => $response,
            ]);

            return redirect()
                ->route('school.finance.index')
                ->withErrors(['payment' => 'Unable to initialize payment right now. Please try again.']);
        }

        return redirect()->away($response['data']['authorization_url']);
    }
}
