<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StoreOrderCustomerMail;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Services\StoreOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class StoreOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('items')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('payment_status'), fn ($q) => $q->where('payment_status', $request->string('payment_status')))
            ->latest('id')
            ->paginate(30);

        $revenueKobo = (int) Order::where('payment_status', 'paid')->sum('total_kobo');

        return view('admin.store.orders', compact('orders', 'revenueKobo'));
    }

    public function updateStatus(Request $request, Order $order, StoreOrderService $orderService)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        if ($data['payment_status'] === 'paid' && $order->payment_status !== 'paid') {
            $order = $orderService->markOrderPaid($order);
            Mail::to($order->email)->queue(new StoreOrderCustomerMail($order, 'payment_confirmation'));
        }

        $order->update([
            'status' => $data['status'],
            'payment_status' => $data['payment_status'],
            'shipped_at' => $data['status'] === 'shipped' ? now() : $order->shipped_at,
            'delivered_at' => $data['status'] === 'delivered' ? now() : $order->delivered_at,
            'cancelled_at' => $data['status'] === 'cancelled' ? now() : null,
        ]);

        if (in_array($data['status'], ['shipped', 'delivered'], true)) {
            Mail::to($order->email)->queue(new StoreOrderCustomerMail($order, 'shipping_update'));
        }

        return back()->with('success', 'Order updated.');
    }

    public function inventory()
    {
        $logs = InventoryLog::with('product', 'order')->latest('id')->paginate(50);

        return view('admin.store.inventory', compact('logs'));
    }

    public function invoice(Order $order)
    {
        $order->load('items');

        return view('admin.store.invoice', compact('order'));
    }
}
