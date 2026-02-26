<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TrainingCohort;
use App\Models\TrainingCourse;
use App\Models\TrainingEnrollment;
use App\Models\TrainingPayment;
use App\Services\PaystackService;
use App\Services\TrainingCouponService;
use App\Services\TrainingPaymentService;
use App\Services\TrainingReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TrainingCheckoutController extends Controller
{
    public function preview()
    {
        $course = TrainingCourse::where('active', true)->orderBy('id')->first();

        return view('instructor-training', [
            'curriculum' => config('training_curriculum'),
            'course' => $course,
        ]);
    }

    public function checkout()
    {
        $course = TrainingCourse::where('active', true)->orderBy('id')->firstOrFail();
        $user = auth()->user();

        if (!$user->referral_code) {
            $user->update(['referral_code' => \App\Models\User::generateUniqueReferralCode()]);
        }

        $existingPaidEnrollment = TrainingEnrollment::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->exists();

        if ($existingPaidEnrollment) {
            return redirect()
                ->route('instructor.training.index')
                ->with('success', 'You already have access to the training course.');
        }

        return view('training.checkout', [
            'course' => $course,
            'referralCode' => request('ref'),
            'myReferralCode' => $user->referral_code,
        ]);
    }

    public function applyCoupon(Request $request, TrainingCouponService $couponService)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:training_courses,id',
            'coupon_code' => 'nullable|string|max:64',
        ]);

        $course = TrainingCourse::findOrFail($data['course_id']);
        $pricing = $couponService->resolveDiscount($course, $data['coupon_code'] ?? null);

        return response()->json([
            'valid' => $pricing['coupon'] !== null,
            'message' => $pricing['message'],
            'subtotal_kobo' => $pricing['subtotal_kobo'],
            'discount_kobo' => $pricing['discount_kobo'],
            'total_kobo' => $pricing['total_kobo'],
        ]);
    }

    public function initialize(
        Request $request,
        TrainingCouponService $couponService,
        TrainingReferralService $referralService,
        PaystackService $paystackService
    ) {
        $data = $request->validate([
            'course_id' => 'required|exists:training_courses,id',
            'coupon_code' => 'nullable|string|max:64',
            'referral_code' => 'nullable|string|max:64',
        ]);

        $user = $request->user();
        $course = TrainingCourse::findOrFail($data['course_id']);
        $pricing = $couponService->resolveDiscount($course, $data['coupon_code'] ?? null);
        $coupon = $pricing['coupon'];

        $cohort = TrainingCohort::where('course_id', $course->id)
            ->whereIn('status', ['ongoing', 'planned'])
            ->orderByRaw("FIELD(status, 'ongoing', 'planned')")
            ->orderBy('start_date')
            ->first();

        if (!$cohort) {
            return back()->withErrors(['course' => 'No active cohort is configured for this course yet.']);
        }

        $enrollment = TrainingEnrollment::firstOrCreate(
            ['cohort_id' => $cohort->id, 'user_id' => $user->id],
            ['status' => 'enrolled']
        );

        $enrollment->update([
            'enrollment_status' => 'pending_payment',
            'payment_status' => 'pending',
            'amount_due_kobo' => $pricing['total_kobo'],
            'amount_paid_kobo' => null,
            'paid_at' => null,
        ]);

        $referralService->attachReferral($user, $data['referral_code'] ?? null);

        $reference = 'GC-TRN-' . strtoupper(Str::random(12));

        $payment = TrainingPayment::create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'enrollment_id' => $enrollment->id,
            'coupon_id' => $coupon?->id,
            'gateway' => 'paystack',
            'reference' => $reference,
            'amount_kobo' => $pricing['total_kobo'],
            'currency' => 'NGN',
            'status' => 'pending',
        ]);

        $callbackUrl = route('training.checkout.callback', ['reference' => $reference]);

        $response = $paystackService->initialize(
            $user->email,
            $payment->amount_kobo,
            $reference,
            $callbackUrl,
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'payment_id' => $payment->id,
            ]
        );

        if (!($response['status'] ?? false)) {
            Log::warning('Paystack initialize failed', ['response' => $response, 'payment_id' => $payment->id]);
            return back()->withErrors(['payment' => 'Unable to initialize payment. Please try again.']);
        }

        $authorizationUrl = $response['data']['authorization_url'] ?? null;
        if (!$authorizationUrl) {
            return back()->withErrors(['payment' => 'Unable to initialize payment authorization URL.']);
        }

        return redirect()->away($authorizationUrl);
    }

    public function callback(
        Request $request,
        PaystackService $paystackService,
        TrainingPaymentService $trainingPaymentService
    ) {
        $reference = $request->query('reference');
        if (!$reference) {
            return redirect()->route('training.checkout')->withErrors(['payment' => 'Missing payment reference.']);
        }

        $payment = TrainingPayment::where('reference', $reference)->first();
        if (!$payment) {
            return redirect()->route('training.checkout')->withErrors(['payment' => 'Payment record not found.']);
        }

        $verification = $paystackService->verify($reference);
        $success = ($verification['status'] ?? false)
            && (($verification['data']['status'] ?? null) === 'success')
            && ((int) ($verification['data']['amount'] ?? 0) >= (int) $payment->amount_kobo);

        if (!$success) {
            $payment->update([
                'status' => 'failed',
                'gateway_response' => $verification,
            ]);

            return redirect()->route('training.checkout')->withErrors(['payment' => 'Payment was not successful.']);
        }

        $trainingPaymentService->completePayment($payment, $verification);

        return redirect()
            ->route('instructor.training.index')
            ->with('success', 'Payment successful. Course access has been unlocked.');
    }

    public function webhook(
        Request $request,
        PaystackService $paystackService,
        TrainingPaymentService $trainingPaymentService
    ) {
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature');

        if (!$paystackService->isValidWebhookSignature($payload, $signature)) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $event = json_decode($payload, true);
        if (($event['event'] ?? null) !== 'charge.success') {
            return response()->json(['status' => 'ignored']);
        }

        $reference = $event['data']['reference'] ?? null;
        if (!$reference) {
            return response()->json(['message' => 'Missing reference'], 422);
        }

        $payment = TrainingPayment::where('reference', $reference)->first();
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $trainingPaymentService->completePayment($payment, $event);

        return response()->json(['status' => 'ok']);
    }
}
