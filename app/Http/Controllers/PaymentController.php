<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\PaystackService;
use App\Services\StoreCartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function initialize(Request $request, PaymentService $paymentService)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'amount' => ['required', 'integer', 'min:100'],
            'purpose' => ['required', Rule::in(Payment::purposes())],
            'metadata' => ['nullable', 'array'],
        ]);

        $payment = $paymentService->createPendingPayment(
            $request->user(),
            $data['email'],
            (int) $data['amount'],
            $data['purpose'],
            $data['metadata'] ?? []
        );

        $response = $paymentService->initialize(
            $payment,
            route('payments.callback', ['reference' => $payment->reference])
        );

        if (!($response['status'] ?? false) || !($response['data']['authorization_url'] ?? null)) {
            $paymentService->markFailed($payment, $response);

            Log::warning('Central payment initialization failed.', [
                'payment_id' => $payment->id,
                'purpose' => $payment->purpose,
                'response' => $response,
            ]);

            return back()->withErrors(['payment' => 'Unable to initialize payment. Please try again.']);
        }

        return redirect()->away($response['data']['authorization_url']);
    }

    public function callback(
        Request $request,
        PaymentService $paymentService,
        StoreCartService $storeCartService
    ) {
        $reference = (string) $request->query('reference');
        $payment = Payment::where('reference', $reference)->first();

        if (!$payment) {
            return redirect()->route('home')->withErrors(['payment' => 'Payment record not found.']);
        }

        $verification = $paymentService->verify($reference);
        $success = ($verification['status'] ?? false)
            && (($verification['data']['status'] ?? null) === 'success')
            && ((int) ($verification['data']['amount'] ?? 0) >= (int) $payment->amount);

        if (!$success) {
            $paymentService->markFailed($payment, $verification);

            return redirect($paymentService->resolveFailureRedirect($payment))
                ->withErrors(['payment' => 'Payment was not successful.']);
        }

        $payment = $paymentService->completeSuccessfulPayment($payment, $verification);

        if ($payment->purpose === Payment::PURPOSE_STORE) {
            $storeCartService->clear();
            $orderId = $payment->metadata['order_id'] ?? null;
            if ($orderId) {
                session()->put('store_last_order_id', $orderId);
            }
        }

        return redirect($paymentService->resolveSuccessRedirect($payment))
            ->with('success', $paymentService->resolveSuccessMessage($payment));
    }

    public function webhook(Request $request, PaymentService $paymentService, PaystackService $paystackService)
    {
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

        $payment = Payment::where('reference', $reference)->first();
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $paymentService->completeSuccessfulPayment($payment, $event);

        return response()->json(['status' => 'ok']);
    }
}
