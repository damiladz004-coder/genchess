<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\SchoolPayment;
use App\Models\SchoolPricing;
use App\Models\Student;

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
}
