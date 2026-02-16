<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolPayment;
use App\Models\SchoolPricing;
use App\Models\Student;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $schools = School::orderBy('school_name')->get();

        $pricings = SchoolPricing::with('school')
            ->orderBy('session', 'desc')
            ->orderBy('term')
            ->get();

        $pricingIndex = $pricings->map(function ($pricing) {
            return [
                'school_id' => $pricing->school_id,
                'term' => $pricing->term,
                'session' => $pricing->session,
                'per_student_amount' => $pricing->per_student_amount,
                'currency' => $pricing->currency,
            ];
        })->values();

        $paymentsQuery = SchoolPayment::with('school')
            ->orderBy('created_at', 'desc');

        if (request('school_id')) {
            $paymentsQuery->where('school_id', request('school_id'));
        }
        if (request('term')) {
            $paymentsQuery->where('term', request('term'));
        }
        if (request('session')) {
            $paymentsQuery->where('session', request('session'));
        }
        if (request('status')) {
            $paymentsQuery->where('status', request('status'));
        }

        $payments = $paymentsQuery->limit(30)->get();

        $summaryQuery = SchoolPayment::query();
        if (request('school_id')) {
            $summaryQuery->where('school_id', request('school_id'));
        }
        if (request('term')) {
            $summaryQuery->where('term', request('term'));
        }
        if (request('session')) {
            $summaryQuery->where('session', request('session'));
        }
        if (request('status')) {
            $summaryQuery->where('status', request('status'));
        }

        $totalDue = (clone $summaryQuery)->sum('total_due');
        $totalPaid = (clone $summaryQuery)->sum('amount_paid');
        $outstanding = max(0, $totalDue - $totalPaid);
        $overdueCount = (clone $summaryQuery)
            ->whereNotNull('second_due_date')
            ->where('second_due_date', '<', now()->toDateString())
            ->whereColumn('amount_paid', '<', 'total_due')
            ->count();

        $terms = $this->terms();
        $sessions = $this->sessions();

        return view('admin.finance.index', [
            'schools' => $schools,
            'pricings' => $pricings,
            'payments' => $payments,
            'pricingIndex' => $pricingIndex,
            'totalDue' => $totalDue,
            'totalPaid' => $totalPaid,
            'outstanding' => $outstanding,
            'overdueCount' => $overdueCount,
            'terms' => $terms,
            'sessions' => $sessions,
        ]);
    }

    public function storePricing(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'term' => 'required|in:' . implode(',', $this->terms()),
            'session' => 'required|in:' . implode(',', $this->sessions()),
            'per_student_amount' => 'required|integer|min:0',
            'currency' => 'required|string|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        SchoolPricing::updateOrCreate(
            [
                'school_id' => $request->school_id,
                'term' => $request->term,
                'session' => $request->session,
            ],
            [
                'per_student_amount' => $request->per_student_amount,
                'currency' => $request->currency,
                'notes' => $request->notes,
                'active' => true,
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Pricing saved.');
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'term' => 'required|in:' . implode(',', $this->terms()),
            'session' => 'required|in:' . implode(',', $this->sessions()),
            'term_start_date' => 'required|date',
            'term_end_date' => 'required|date|after:term_start_date',
            'student_count' => 'required|integer|min:0',
            'per_student_amount' => 'nullable|integer|min:0',
            'amount_paid' => 'required|integer|min:0',
            'due_date' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $pricing = SchoolPricing::where('school_id', $request->school_id)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->first();

        if (!$pricing) {
            return redirect()
                ->back()
                ->withErrors(['per_student_amount' => 'Set pricing for this school/term/session before recording a payment.'])
                ->withInput();
        }

        $rate = $pricing->per_student_amount;
        $totalDue = $request->student_count * $rate;
        $paidAt = $request->amount_paid >= $totalDue ? now() : null;
        $status = $request->amount_paid <= 0 ? 'pending' : ($request->amount_paid >= $totalDue ? 'paid' : 'partial');

        $termStart = \Carbon\Carbon::parse($request->term_start_date);
        $termEnd = \Carbon\Carbon::parse($request->term_end_date);
        $firstDueDate = $termStart->copy()->addWeeks(3);
        $secondDueDate = $termEnd->copy()->subWeek();
        $firstAmount = (int) floor($totalDue / 2);
        $secondAmount = $totalDue - $firstAmount;

        SchoolPayment::create([
            'school_id' => $request->school_id,
            'term' => $request->term,
            'session' => $request->session,
            'term_start_date' => $request->term_start_date,
            'term_end_date' => $request->term_end_date,
            'first_due_date' => $firstDueDate->toDateString(),
            'second_due_date' => $secondDueDate->toDateString(),
            'first_amount' => $firstAmount,
            'second_amount' => $secondAmount,
            'student_count' => $request->student_count,
            'per_student_amount' => $rate,
            'total_due' => $totalDue,
            'amount_paid' => $request->amount_paid,
            'status' => $status,
            'due_date' => $request->due_date,
            'paid_at' => $paidAt,
            'reference' => $request->reference,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Payment record created.');
    }

    public function generatePayment(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'term' => 'required|in:' . implode(',', $this->terms()),
            'session' => 'required|in:' . implode(',', $this->sessions()),
            'term_start_date' => 'required|date',
            'term_end_date' => 'required|date|after:term_start_date',
        ]);

        $pricing = SchoolPricing::where('school_id', $request->school_id)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->first();

        if (!$pricing) {
            return redirect()
                ->back()
                ->withErrors(['per_student_amount' => 'Set pricing for this school/term/session before generating a payment.'])
                ->withInput();
        }

        $exists = SchoolPayment::where('school_id', $request->school_id)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withErrors(['term' => 'A payment record already exists for this school/term/session.'])
                ->withInput();
        }

        $studentCount = Student::where('school_id', $request->school_id)->count();
        $rate = $pricing->per_student_amount;
        $totalDue = $studentCount * $rate;

        $termStart = \Carbon\Carbon::parse($request->term_start_date);
        $termEnd = \Carbon\Carbon::parse($request->term_end_date);
        $firstDueDate = $termStart->copy()->addWeeks(3);
        $secondDueDate = $termEnd->copy()->subWeek();
        $firstAmount = (int) floor($totalDue / 2);
        $secondAmount = $totalDue - $firstAmount;

        SchoolPayment::create([
            'school_id' => $request->school_id,
            'term' => $request->term,
            'session' => $request->session,
            'term_start_date' => $request->term_start_date,
            'term_end_date' => $request->term_end_date,
            'first_due_date' => $firstDueDate->toDateString(),
            'second_due_date' => $secondDueDate->toDateString(),
            'first_amount' => $firstAmount,
            'second_amount' => $secondAmount,
            'student_count' => $studentCount,
            'per_student_amount' => $rate,
            'total_due' => $totalDue,
            'amount_paid' => 0,
            'status' => $totalDue > 0 ? 'pending' : 'paid',
            'paid_at' => $totalDue > 0 ? null : now(),
            'notes' => 'Auto-generated from student count.',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Payment record generated.');
    }

    public function generatePaymentsBulk(Request $request)
    {
        $request->validate([
            'term' => 'required|in:' . implode(',', $this->terms()),
            'session' => 'required|in:' . implode(',', $this->sessions()),
            'term_start_date' => 'required|date',
            'term_end_date' => 'required|date|after:term_start_date',
        ]);

        $termStart = \Carbon\Carbon::parse($request->term_start_date);
        $termEnd = \Carbon\Carbon::parse($request->term_end_date);
        $firstDueDate = $termStart->copy()->addWeeks(3);
        $secondDueDate = $termEnd->copy()->subWeek();

        $schools = School::orderBy('school_name')->get();
        $created = 0;
        $skipped = 0;

        foreach ($schools as $school) {
            $pricing = SchoolPricing::where('school_id', $school->id)
                ->where('term', $request->term)
                ->where('session', $request->session)
                ->first();

            if (!$pricing) {
                $skipped++;
                continue;
            }

            $exists = SchoolPayment::where('school_id', $school->id)
                ->where('term', $request->term)
                ->where('session', $request->session)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            $studentCount = Student::where('school_id', $school->id)->count();
            $rate = $pricing->per_student_amount;
            $totalDue = $studentCount * $rate;
            $firstAmount = (int) floor($totalDue / 2);
            $secondAmount = $totalDue - $firstAmount;

            SchoolPayment::create([
                'school_id' => $school->id,
                'term' => $request->term,
                'session' => $request->session,
                'term_start_date' => $request->term_start_date,
                'term_end_date' => $request->term_end_date,
                'first_due_date' => $firstDueDate->toDateString(),
                'second_due_date' => $secondDueDate->toDateString(),
                'first_amount' => $firstAmount,
                'second_amount' => $secondAmount,
                'student_count' => $studentCount,
                'per_student_amount' => $rate,
                'total_due' => $totalDue,
                'amount_paid' => 0,
                'status' => $totalDue > 0 ? 'pending' : 'paid',
                'paid_at' => $totalDue > 0 ? null : now(),
                'notes' => 'Auto-generated (bulk).',
            ]);

            $created++;
        }

        return redirect()
            ->back()
            ->with('success', "Bulk generation complete. Created: {$created}, Skipped: {$skipped}.");
    }

    public function previewGeneratePaymentsBulk(Request $request)
    {
        $request->validate([
            'term' => 'required|in:' . implode(',', $this->terms()),
            'session' => 'required|in:' . implode(',', $this->sessions()),
            'term_start_date' => 'required|date',
            'term_end_date' => 'required|date|after:term_start_date',
        ]);

        $schools = School::orderBy('school_name')->get();
        $creatable = 0;
        $skippedNoPricing = 0;
        $skippedExisting = 0;
        $creatableList = [];
        $skippedNoPricingList = [];
        $skippedExistingList = [];

        foreach ($schools as $school) {
            $pricing = SchoolPricing::where('school_id', $school->id)
                ->where('term', $request->term)
                ->where('session', $request->session)
                ->first();

            if (!$pricing) {
                $skippedNoPricing++;
                $skippedNoPricingList[] = $school->school_name;
                continue;
            }

            $exists = SchoolPayment::where('school_id', $school->id)
                ->where('term', $request->term)
                ->where('session', $request->session)
                ->exists();

            if ($exists) {
                $skippedExisting++;
                $skippedExistingList[] = $school->school_name;
                continue;
            }

            $creatable++;
            $creatableList[] = $school->school_name;
        }

        return redirect()
            ->back()
            ->with('bulk_preview', [
                'term' => $request->term,
                'session' => $request->session,
                'term_start_date' => $request->term_start_date,
                'term_end_date' => $request->term_end_date,
                'creatable' => $creatable,
                'skipped_no_pricing' => $skippedNoPricing,
                'skipped_existing' => $skippedExisting,
                'creatable_list' => $creatableList,
                'skipped_no_pricing_list' => $skippedNoPricingList,
                'skipped_existing_list' => $skippedExistingList,
            ]);
    }

    public function exportPayments()
    {
        $query = SchoolPayment::with('school')
            ->orderBy('created_at', 'desc');

        if (request('school_id')) {
            $query->where('school_id', request('school_id'));
        }

        if (request('term')) {
            $query->where('term', request('term'));
        }

        if (request('session')) {
            $query->where('session', request('session'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $rows = $query->get()->map(function ($p) {
                return [
                    'school' => $p->school->school_name ?? 'N/A',
                    'term' => $p->term,
                    'session' => $p->session,
                    'student_count' => $p->student_count,
                    'rate' => $p->per_student_amount,
                    'total_due' => $p->total_due,
                    'amount_paid' => $p->amount_paid,
                    'status' => $p->status,
                    'term_start_date' => optional($p->term_start_date)->format('Y-m-d'),
                    'term_end_date' => optional($p->term_end_date)->format('Y-m-d'),
                    'first_due_date' => optional($p->first_due_date)->format('Y-m-d'),
                    'second_due_date' => optional($p->second_due_date)->format('Y-m-d'),
                    'created_at' => optional($p->created_at)->format('Y-m-d'),
                ];
            });

        $headers = array_keys($rows->first() ?? [
            'school' => '',
            'term' => '',
            'session' => '',
            'student_count' => '',
            'rate' => '',
            'total_due' => '',
            'amount_paid' => '',
            'status' => '',
            'term_start_date' => '',
            'term_end_date' => '',
            'first_due_date' => '',
            'second_due_date' => '',
            'created_at' => '',
        ]);

        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, 'school_payments.csv');
    }

    private function terms(): array
    {
        return ['Term 1', 'Term 2', 'Term 3'];
    }

    private function sessions(): array
    {
        $now = now();
        $startYear = $now->month >= 9 ? $now->year : $now->year - 1;

        $sessions = [];
        for ($i = 0; $i < 4; $i++) {
            $sessions[] = ($startYear + $i) . '/' . ($startYear + $i + 1);
        }

        return $sessions;
    }

}
