<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\StoreCartService;
use Illuminate\Http\Request;

class StoreCheckoutController extends Controller
{
    public function show(StoreCartService $cartService)
    {
        return $this->storeUnavailable();
    }

    public function placeOrder(
        Request $request
    ) {
        return redirect()->route('store.index')
            ->withErrors(['store' => 'The chess store is temporarily unavailable while product images are being uploaded.']);
    }

    public function callback(Request $request)
    {
        return redirect()->route('store.index')
            ->withErrors(['store' => 'The chess store is temporarily unavailable while product images are being uploaded.']);
    }

    public function webhook(Request $request, PaystackService $paystackService, StoreOrderService $orderService)
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
        $order = Order::where('reference', $reference)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $orderService->markOrderPaid($order, $event);
        Mail::to($order->email)->queue(new StoreOrderCustomerMail($order, 'payment_confirmation'));

        return response()->json(['status' => 'ok']);
    }

    public function success(Order $order)
    {
        return $this->storeUnavailable();
    }

    private function storeUnavailable()
    {
        return response()
            ->view('store.unavailable')
            ->setStatusCode(503);
    }
}
