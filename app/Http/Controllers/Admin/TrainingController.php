<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Coupon;
use App\Models\TrainingCohort;
use App\Models\TrainingCourse;
use App\Models\TrainingEnrollment;
use App\Models\TrainingPayment;
use App\Models\TrainingReferral;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    public function index()
    {
        $courses = TrainingCourse::orderBy('title')->get();
        $users = User::orderBy('name')->limit(200)->get(['id', 'name', 'email']);
        $cohorts = TrainingCohort::with('course')
            ->orderBy('start_date', 'desc')
            ->get();
        $hasCouponsTable = Schema::hasTable('coupons');
        $hasPaymentsTable = Schema::hasTable('training_payments');
        $hasReferralTable = Schema::hasTable('training_referrals');
        $hasCouponRedemptionsTable = Schema::hasTable('training_coupon_redemptions');
        $hasEnrollmentPaymentStatus = Schema::hasColumn('training_enrollments', 'payment_status');

        $coupons = $hasCouponsTable
            ? Coupon::orderBy('created_at', 'desc')->limit(40)->get()
            : collect();

        $stats = [
            'total_enrollments' => $hasEnrollmentPaymentStatus
                ? TrainingEnrollment::where('payment_status', 'paid')->count()
                : 0,
            'revenue_kobo' => $hasPaymentsTable
                ? (int) TrainingPayment::where('status', 'paid')->sum('amount_kobo')
                : 0,
            'coupon_redemptions' => $hasCouponRedemptionsTable
                ? (int) DB::table('training_coupon_redemptions')->count()
                : 0,
            'active_coupons' => $hasCouponsTable
                ? Coupon::where('status', 'active')->count()
                : 0,
        ];

        $referralLeaderboard = $hasReferralTable
            ? TrainingReferral::select('referrer_id', DB::raw('COUNT(*) as paid_referrals'))
                ->where('payment_status', 'paid')
                ->groupBy('referrer_id')
                ->with('referrer:id,name,email')
                ->orderByDesc('paid_referrals')
                ->limit(10)
                ->get()
            : collect();

        return view('admin.training.index', compact('courses', 'users', 'cohorts', 'coupons', 'stats', 'referralLeaderboard'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_hours' => 'required|integer|min:0|max:500',
            'duration_minutes' => 'required|integer|min:0|max:59',
            'price_naira' => 'nullable|numeric|min:0',
            'discount_price_naira' => 'nullable|numeric|min:0',
        ]);

        $durationHours = (int) $request->duration_hours;
        $durationMinutes = (int) $request->duration_minutes;

        if ($durationHours === 0 && $durationMinutes === 0) {
            return redirect()
                ->back()
                ->withErrors(['duration_hours' => 'Duration must be at least 1 minute.'])
                ->withInput();
        }

        $priceKobo = (int) round(((float) ($request->price_naira ?? 35000)) * 100);
        $discountPriceKobo = (int) round(((float) ($request->discount_price_naira ?? 25000)) * 100);
        $totalMinutes = ($durationHours * 60) + $durationMinutes;
        $legacyDurationWeeks = max(1, (int) ceil($totalMinutes / (7 * 24 * 60)));

        $courseData = [
            'title' => $request->title,
            'description' => $request->description,
            'duration_weeks' => $legacyDurationWeeks,
            'price_kobo' => $priceKobo,
            'currency' => 'NGN',
            'discount_price_kobo' => $discountPriceKobo,
            'active' => true,
        ];

        if (Schema::hasColumn('training_courses', 'duration_hours')) {
            $courseData['duration_hours'] = $durationHours;
        }

        if (Schema::hasColumn('training_courses', 'duration_minutes')) {
            $courseData['duration_minutes'] = $durationMinutes;
        }

        TrainingCourse::create($courseData);

        return redirect()->back()->with('success', 'Course created.');
    }

    public function storeCohort(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:training_courses,id',
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,ongoing,completed',
        ]);

        TrainingCohort::create($request->only([
            'course_id',
            'name',
            'start_date',
            'end_date',
            'status',
        ]));

        return redirect()->back()->with('success', 'Cohort created.');
    }

    public function showCohort(TrainingCohort $cohort)
    {
        $cohort->load(['course', 'enrollments.user', 'enrollments.certification']);
        $instructors = User::where('role', 'instructor')->orderBy('name')->get();

        return view('admin.training.cohort', compact('cohort', 'instructors'));
    }

    public function enroll(Request $request, TrainingCohort $cohort)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        TrainingEnrollment::firstOrCreate([
            'cohort_id' => $cohort->id,
            'user_id' => $request->user_id,
        ]);

        return redirect()->back()->with('success', 'Instructor enrolled.');
    }

    public function updateEnrollment(Request $request, TrainingEnrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:enrolled,completed,dropped',
        ]);

        $completedAt = $request->status === 'completed' ? now() : null;

        $enrollment->update([
            'status' => $request->status,
            'completed_at' => $completedAt,
        ]);

        return redirect()->back()->with('success', 'Enrollment updated.');
    }

    public function issueCertificate(TrainingEnrollment $enrollment)
    {
        if ($enrollment->certification) {
            return redirect()->back()->with('success', 'Certificate already issued.');
        }

        if (!$enrollment->isEligibleForCertification()) {
            return redirect()->back()->with(
                'success',
                'Certificate not issued. Enrollment must complete quizzes, assignments, teaching practice, and mentor approval.'
            );
        }

        Certification::create([
            'enrollment_id' => $enrollment->id,
            'certificate_code' => strtoupper(Str::random(10)),
            'issued_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Certificate issued.');
    }

    public function storeCoupon(Request $request)
    {
        if (!Schema::hasTable('coupons')) {
            return back()->withErrors(['coupon' => 'Coupons table is missing. Run migrations first.']);
        }

        $data = $request->validate([
            'code' => 'required|string|max:64|unique:coupons,code',
            'type' => 'required|in:early_bird,referral,custom',
            'usage_limit' => 'nullable|integer|min:1',
            'expiry_date' => 'nullable|date|after:today',
            'status' => 'required|in:active,inactive',
        ]);

        Coupon::create([
            'code' => strtoupper(trim($data['code'])),
            'type' => $data['type'],
            'discount_type' => 'fixed_amount',
            'discount_value' => 1000000,
            'usage_limit' => $data['usage_limit'] ?? null,
            'used_count' => 0,
            'expiry_date' => $data['expiry_date'] ?? null,
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Coupon created.');
    }

    public function updateCoupon(Request $request, Coupon $coupon)
    {
        if (!Schema::hasTable('coupons')) {
            return back()->withErrors(['coupon' => 'Coupons table is missing. Run migrations first.']);
        }

        $data = $request->validate([
            'usage_limit' => 'nullable|integer|min:1',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        $coupon->update($data);

        return back()->with('success', 'Coupon updated.');
    }

    public function assignManualCoupon(Request $request)
    {
        if (!Schema::hasTable('coupons')) {
            return back()->withErrors(['coupon' => 'Coupons table is missing. Run migrations first.']);
        }

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'expiry_date' => 'nullable|date|after:today',
        ]);

        $code = 'MAN-' . strtoupper(Str::random(8));

        Coupon::create([
            'code' => $code,
            'type' => 'custom',
            'discount_type' => 'fixed_amount',
            'discount_value' => 1000000,
            'usage_limit' => 1,
            'used_count' => 0,
            'expiry_date' => $data['expiry_date'] ?? now()->addMonths(3),
            'status' => 'active',
        ]);

        $user = User::findOrFail($data['user_id']);

        return back()->with('success', "Manual coupon {$code} generated for {$user->email}.");
    }
}
