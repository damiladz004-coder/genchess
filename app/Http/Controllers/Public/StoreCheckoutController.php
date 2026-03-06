<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\StoreAdminAlertMail;
use App\Mail\StoreOrderCustomerMail;
use App\Models\Order;
use App\Services\PaystackService;
use App\Services\DeliveryFeeService;
use App\Services\StoreCartService;
use App\Services\StoreOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StoreCheckoutController extends Controller
{
    public function show(StoreCartService $cartService)
    {
        $cart = $cartService->summary();
        if ($cart['count'] < 1) {
            return redirect()->route('store.index')->withErrors(['cart' => 'Your cart is empty.']);
        }

        return view('store.checkout', compact('cart'));
    }

    public function placeOrder(
        Request $request,
        StoreCartService $cartService,
        StoreOrderService $orderService,
        DeliveryFeeService $deliveryFeeService,
        PaystackService $paystackService
    ) {
        $cart = $cartService->summary();
        if ($cart['count'] < 1) {
            return redirect()->route('store.index')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'delivery_address' => 'required|string|max:2000',
            'state' => 'required|string|max:255',
            'order_type' => 'required|in:individual,school,organization',
            'payment_method' => 'required|in:paystack,bank_transfer',
            'notes' => 'nullable|string|max:2000',
        ]);
        $data['state'] = trim($data['state']);
        $data['delivery_fee'] = $deliveryFeeService->calculateDeliveryFee($data['state']);

        try {
            $order = $orderService->createOrderFromCart($data, $cart, $data['payment_method']);
        } catch (\Throwable $e) {
            return back()->withErrors(['checkout' => $e->getMessage()]);
        }

        Mail::to($order->email)->queue(new StoreOrderCustomerMail($order, 'order_confirmation'));
        Mail::to(config('mail.from.address'))->queue(new StoreAdminAlertMail(order: $order));

        if ($data['payment_method'] === 'bank_transfer') {
            $cartService->clear();
            session()->put('store_last_order_id', $order->id);
            return redirect()->route('store.checkout.success', $order)->with('success', 'Order created. Awaiting payment verification.');
        }

        $reference = 'GC-STORE-' . strtoupper(Str::random(12));
        $order->update(['reference' => $reference]);

        $response = $paystackService->initialize(
            $order->email,
            (int) $order->total_kobo,
            $reference,
            route('store.checkout.callback', ['reference' => $reference]),
            [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]
        );

        if (!($response['status'] ?? false)) {
            Log::warning('Store paystack initialize failed', ['order_id' => $order->id, 'response' => $response]);
            return redirect()->route('store.checkout')->withErrors(['payment' => 'Unable to initialize online payment.']);
        }

        return redirect()->away($response['data']['authorization_url'] ?? route('store.checkout'));
    }

    public function callback(
        Request $request,
        PaystackService $paystackService,
        StoreOrderService $orderService,
        StoreCartService $cartService
    ) {
        $reference = (string) $request->query('reference');
        $order = Order::where('reference', $reference)->first();

        if (!$order) {
            return redirect()->route('store.checkout')->withErrors(['payment' => 'Order payment reference not found.']);
        }

        $verification = $paystackService->verify($reference);
        $success = ($verification['status'] ?? false)
            && (($verification['data']['status'] ?? '') === 'success')
            && ((int) ($verification['data']['amount'] ?? 0) >= (int) $order->total_kobo);

        if (!$success) {
            $order->update([
                'payment_status' => 'failed',
                'status' => 'pending',
                'paystack_response' => $verification,
            ]);

            return redirect()->route('store.checkout')->withErrors(['payment' => 'Payment was not successful.']);
        }

        $order = $orderService->markOrderPaid($order, $verification);
        $cartService->clear();
        session()->put('store_last_order_id', $order->id);

        Mail::to($order->email)->queue(new StoreOrderCustomerMail($order, 'payment_confirmation'));

        return redirect()->route('store.checkout.success', $order)->with('success', 'Payment successful. Order confirmed.');
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
        $canView = false;

        if (auth()->check()) {
            $isOwner = $order->user_id && (int) $order->user_id === (int) auth()->id();
            $isSuperAdmin = auth()->user()?->role === 'super_admin';
            $canView = $isOwner || $isSuperAdmin;
        } else {
            $canView = (int) session('store_last_order_id') === (int) $order->id;
        }

        abort_unless($canView, 403);
        session()->forget('store_last_order_id');

        return view('store.success', compact('order'));
    }
}
