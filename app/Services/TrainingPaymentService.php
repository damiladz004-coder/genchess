<?php

namespace App\Services;

use App\Mail\TrainingEnrollmentWelcomeMail;
use App\Models\TrainingCohort;
use App\Models\TrainingEnrollment;
use App\Models\TrainingInvoice;
use App\Models\TrainingPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TrainingPaymentService
{
    public function __construct(
        protected TrainingCouponService $couponService,
        protected TrainingReferralService $referralService
    ) {
    }

    public function completePayment(TrainingPayment $payment, array $gatewayResponse = []): TrainingPayment
    {
        return DB::transaction(function () use ($payment, $gatewayResponse): TrainingPayment {
            $payment = TrainingPayment::whereKey($payment->id)->lockForUpdate()->firstOrFail();

            if ($payment->status === 'paid') {
                return $payment;
            }

            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'gateway_response' => $gatewayResponse,
            ]);

            $enrollment = $payment->enrollment;
            if (!$enrollment) {
                $cohort = TrainingCohort::where('course_id', $payment->course_id)
                    ->whereIn('status', ['ongoing', 'planned'])
                    ->orderByRaw("FIELD(status, 'ongoing', 'planned')")
                    ->orderBy('start_date')
                    ->first();

                if (!$cohort) {
                    $cohort = TrainingCohort::where('course_id', $payment->course_id)
                        ->latest('start_date')
                        ->firstOrFail();
                }

                $enrollment = TrainingEnrollment::firstOrCreate(
                    [
                        'cohort_id' => $cohort->id,
                        'user_id' => $payment->user_id,
                    ],
                    [
                        'status' => 'enrolled',
                        'enrollment_status' => 'pending_payment',
                        'payment_status' => 'pending',
                        'amount_due_kobo' => $payment->amount_kobo,
                    ]
                );
            }

            $enrollment->update([
                'status' => 'enrolled',
                'enrollment_status' => 'enrolled',
                'payment_status' => 'paid',
                'amount_due_kobo' => $payment->amount_kobo,
                'amount_paid_kobo' => $payment->amount_kobo,
                'paid_at' => now(),
            ]);

            $payment->update(['enrollment_id' => $enrollment->id]);

            if ($payment->coupon_id) {
                $coupon = $payment->coupon;
                if ($coupon) {
                    $discount = max(0, ((int) $payment->course->price_kobo) - ((int) $payment->amount_kobo));
                    $this->couponService->redeem($coupon, $payment->user, $enrollment, $discount);
                }
            }

            $invoice = $payment->invoice ?: TrainingInvoice::create([
                'payment_id' => $payment->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'subtotal_kobo' => (int) $payment->course->price_kobo,
                'discount_kobo' => max(0, ((int) $payment->course->price_kobo) - ((int) $payment->amount_kobo)),
                'total_kobo' => (int) $payment->amount_kobo,
                'currency' => $payment->currency,
                'issued_at' => now(),
            ]);

            $this->referralService->markReferredUserPaid($payment->user);

            Mail::to($payment->user->email)->queue(new TrainingEnrollmentWelcomeMail($payment, $invoice));

            return $payment->fresh(['invoice', 'enrollment', 'course', 'user']);
        });
    }

    protected function generateInvoiceNumber(): string
    {
        do {
            $invoiceNumber = 'GC-INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (TrainingInvoice::where('invoice_number', $invoiceNumber)->exists());

        return $invoiceNumber;
    }
}

