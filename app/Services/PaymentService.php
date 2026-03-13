<?php

namespace App\Services;

use App\Mail\SchoolPortalAccessMail;
use App\Mail\StoreOrderCustomerMail;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SchoolPayment;
use App\Models\SchoolRequest;
use App\Models\TrainingPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        protected PaystackService $paystackService,
        protected TrainingPaymentService $trainingPaymentService,
        protected StoreOrderService $storeOrderService,
        protected WhatsAppMessageService $whatsAppService
    ) {
    }

    public function createPendingPayment(
        ?User $user,
        string $email,
        int $amount,
        string $purpose,
        array $metadata = [],
        ?string $reference = null
    ): Payment {
        return Payment::create([
            'user_id' => $user?->id,
            'email' => $email,
            'reference' => $reference ?: $this->generateReference($purpose),
            'amount' => max(0, $amount),
            'purpose' => $purpose,
            'status' => 'pending',
            'metadata' => $metadata,
        ]);
    }

    public function initialize(Payment $payment, string $callbackUrl): array
    {
        Log::info('Initializing Paystack payment.', [
            'payment_id' => $payment->id,
            'purpose' => $payment->purpose,
            'reference' => $payment->reference,
            'amount' => $payment->amount,
        ]);

        return $this->paystackService->initialize(
            $payment->email,
            (int) $payment->amount,
            $payment->reference,
            $callbackUrl,
            array_filter([
                'payment_id' => $payment->id,
                'purpose' => $payment->purpose,
            ] + ($payment->metadata ?? []), fn ($value) => $value !== null)
        );
    }

    public function verify(string $reference): array
    {
        return $this->paystackService->verify($reference);
    }

    public function markFailed(Payment $payment, array $gatewayResponse = []): Payment
    {
        $payment->update([
            'status' => 'failed',
            'gateway_response' => $gatewayResponse,
        ]);

        return $payment->fresh();
    }

    public function completeSuccessfulPayment(Payment $payment, array $gatewayResponse = []): Payment
    {
        return DB::transaction(function () use ($payment, $gatewayResponse): Payment {
            $payment = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();

            if ($payment->status !== 'paid') {
                $payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'gateway_response' => $gatewayResponse,
                ]);
            }

            match ($payment->purpose) {
                Payment::PURPOSE_TRAINING => $this->completeTrainingPayment($payment, $gatewayResponse),
                Payment::PURPOSE_STORE => $this->completeStorePayment($payment, $gatewayResponse),
                Payment::PURPOSE_SCHOOL => $this->completeSchoolPayment($payment),
                Payment::PURPOSE_CONSULTATION,
                Payment::PURPOSE_TOURNAMENT => null,
                default => null,
            };

            Log::info('Paystack payment completed.', [
                'payment_id' => $payment->id,
                'purpose' => $payment->purpose,
                'reference' => $payment->reference,
            ]);

            return $payment->fresh();
        });
    }

    public function resolveSuccessRedirect(Payment $payment): string
    {
        return match ($payment->purpose) {
            Payment::PURPOSE_TRAINING => route('instructor.training.index'),
            Payment::PURPOSE_STORE => $this->resolveStoreSuccessRedirect($payment),
            Payment::PURPOSE_SCHOOL => $this->resolveSchoolSuccessRedirect($payment),
            Payment::PURPOSE_CONSULTATION => route('chess.communities.homes'),
            Payment::PURPOSE_TOURNAMENT => route('tournaments'),
            default => route('home'),
        };
    }

    public function resolveFailureRedirect(Payment $payment): string
    {
        return match ($payment->purpose) {
            Payment::PURPOSE_TRAINING => route('training.checkout'),
            Payment::PURPOSE_STORE => route('store.checkout'),
            Payment::PURPOSE_SCHOOL => $this->resolveSchoolFailureRedirect($payment),
            Payment::PURPOSE_CONSULTATION => route('chess.communities.homes'),
            Payment::PURPOSE_TOURNAMENT => route('tournaments'),
            default => route('home'),
        };
    }

    public function resolveSuccessMessage(Payment $payment): string
    {
        if (
            $payment->purpose === Payment::PURPOSE_SCHOOL
            && isset($payment->metadata['school_payment_id'])
        ) {
            return 'Payment received successfully. Your school invoice has been updated.';
        }

        return match ($payment->purpose) {
            Payment::PURPOSE_TRAINING => 'Payment successful. Course access has been unlocked.',
            Payment::PURPOSE_STORE => 'Payment successful. Order confirmed.',
            Payment::PURPOSE_SCHOOL => 'Payment received successfully. Your school application will continue to onboarding after approval.',
            Payment::PURPOSE_CONSULTATION => 'Consultation payment received successfully. Our team will contact you with the meeting schedule.',
            Payment::PURPOSE_TOURNAMENT => 'Tournament payment received successfully.',
            default => 'Payment successful.',
        };
    }

    protected function completeTrainingPayment(Payment $payment, array $gatewayResponse = []): void
    {
        $trainingPaymentId = $payment->metadata['training_payment_id'] ?? null;

        $trainingPayment = $trainingPaymentId
            ? TrainingPayment::find($trainingPaymentId)
            : null;

        if (!$trainingPayment) {
            $trainingPayment = TrainingPayment::where('reference', $payment->reference)->first();
        }

        if ($trainingPayment) {
            $this->trainingPaymentService->completePayment($trainingPayment, $gatewayResponse);
        }
    }

    protected function completeStorePayment(Payment $payment, array $gatewayResponse = []): void
    {
        $orderId = $payment->metadata['order_id'] ?? null;

        $order = $orderId
            ? Order::find($orderId)
            : null;

        if (!$order) {
            $order = Order::where('reference', $payment->reference)->first();
        }

        if ($order) {
            $wasPaid = $order->payment_status === 'paid';
            $order = $this->storeOrderService->markOrderPaid($order, $gatewayResponse);

            if (!$wasPaid) {
                Mail::to($order->email)->queue(new StoreOrderCustomerMail($order, 'payment_confirmation'));
            }
        }
    }

    protected function resolveStoreSuccessRedirect(Payment $payment): string
    {
        $orderId = $payment->metadata['order_id'] ?? null;
        $order = $orderId ? Order::find($orderId) : Order::where('reference', $payment->reference)->first();

        return $order ? route('store.checkout.success', $order) : route('store.checkout');
    }

    protected function resolveSchoolSuccessRedirect(Payment $payment): string
    {
        if (isset($payment->metadata['school_payment_id'])) {
            return route('school.finance.index');
        }

        $schoolRequestId = $payment->metadata['school_request_id'] ?? null;
        $schoolRequest = $schoolRequestId ? SchoolRequest::find($schoolRequestId) : null;

        if ($schoolRequest && $schoolRequest->status === 'approved') {
            return URL::signedRoute('school.portal.onboarding.create', ['schoolRequest' => $schoolRequest->id]);
        }

        return route('register.school');
    }

    protected function resolveSchoolFailureRedirect(Payment $payment): string
    {
        if (isset($payment->metadata['school_payment_id'])) {
            return route('school.finance.index');
        }

        return route('register.school');
    }

    protected function completeSchoolPayment(Payment $payment): void
    {
        $schoolPaymentId = $payment->metadata['school_payment_id'] ?? null;
        $metadata = $payment->metadata ?? [];

        if ($schoolPaymentId && (($payment->metadata['school_payment_applied'] ?? false) !== true)) {
            $schoolPayment = SchoolPayment::whereKey($schoolPaymentId)->lockForUpdate()->first();
            if ($schoolPayment) {
                $amountNaira = max(0, (int) round(((int) $payment->amount) / 100));
                if ($amountNaira > 0) {
                    $newAmountPaid = min((int) $schoolPayment->total_due, (int) $schoolPayment->amount_paid + $amountNaira);
                    $status = $newAmountPaid <= 0
                        ? 'pending'
                        : ($newAmountPaid >= (int) $schoolPayment->total_due ? 'paid' : 'partial');

                    $schoolPayment->update([
                        'amount_paid' => $newAmountPaid,
                        'status' => $status,
                        'paid_at' => $status === 'paid' ? now() : $schoolPayment->paid_at,
                        'reference' => $payment->reference,
                    ]);
                }
            }

            $metadata['school_payment_applied'] = true;
            $metadata['school_payment_applied_at'] = now()->toIso8601String();
        }

        $schoolRequestId = $payment->metadata['school_request_id'] ?? null;
        if ($schoolRequestId && (($payment->metadata['school_request_unlock_processed'] ?? false) !== true)) {
            $schoolRequest = SchoolRequest::find($schoolRequestId);

            // If admin has already approved the request, payment should trigger portal access delivery.
            if ($schoolRequest && $schoolRequest->status === 'approved' && $schoolRequest->school_id) {
                $onboardingUrl = URL::signedRoute('school.portal.onboarding.create', ['schoolRequest' => $schoolRequest->id]);

                if (!$schoolRequest->portal_link_sent_at) {
                    Mail::to($schoolRequest->email)->queue(new SchoolPortalAccessMail($schoolRequest, $onboardingUrl));
                    $schoolRequest->forceFill(['portal_link_sent_at' => now()])->saveQuietly();
                }

                if (!$schoolRequest->portal_whatsapp_sent_at) {
                    if ($this->whatsAppService->send(
                        $schoolRequest->phone,
                        "Your Genchess school portal access link is ready: {$onboardingUrl}",
                        ['school_request_id' => $schoolRequest->id, 'type' => 'school_portal']
                    )) {
                        $schoolRequest->forceFill(['portal_whatsapp_sent_at' => now()])->saveQuietly();
                    }
                }
            }

            $metadata['school_request_unlock_processed'] = true;
            $metadata['school_request_unlock_processed_at'] = now()->toIso8601String();
        }

        $payment->update(['metadata' => $metadata]);
    }

    protected function generateReference(string $purpose): string
    {
        $prefix = match ($purpose) {
            Payment::PURPOSE_TRAINING => 'GC-TRN',
            Payment::PURPOSE_STORE => 'GC-STORE',
            Payment::PURPOSE_SCHOOL => 'GC-SCH',
            Payment::PURPOSE_CONSULTATION => 'GC-CONS',
            Payment::PURPOSE_TOURNAMENT => 'GC-TRNMT',
            default => 'GC-PAY',
        };

        do {
            $reference = $prefix . '-' . strtoupper(Str::random(12));
        } while (Payment::where('reference', $reference)->exists());

        return $reference;
    }
}
