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
            'address_verified' => 'nullable|boolean',
            'stock_confirmed' => 'nullable|boolean',
            'packed' => 'nullable|boolean',
            'quality_checked' => 'nullable|boolean',
            'courier_name' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
            'delivery_confirmed' => 'nullable|boolean',
        ]);

        if ($data['payment_status'] === 'paid' && $order->payment_status !== 'paid') {
            $order = $orderService->markOrderPaid($order);
            Mail::to($order->email)->queue(new StoreOrderCustomerMail($order, 'payment_confirmation'));
        }

        $addressVerifiedAt = $this->checklistTimestamp($order->address_verified_at, $request->boolean('address_verified'));
        $stockConfirmedAt = $this->checklistTimestamp($order->stock_confirmed_at, $request->boolean('stock_confirmed'));
        $packedAt = $this->checklistTimestamp($order->packed_at, $request->boolean('packed'));
        $qualityCheckedAt = $this->checklistTimestamp($order->quality_checked_at, $request->boolean('quality_checked'));
        $deliveryConfirmedAt = $this->checklistTimestamp($order->delivery_confirmed_at, $request->boolean('delivery_confirmed'));
        $courierName = trim((string) ($data['courier_name'] ?? ''));
        $trackingNumber = trim((string) ($data['tracking_number'] ?? ''));
        $courierName = $courierName !== '' ? $courierName : null;
        $trackingNumber = $trackingNumber !== '' ? $trackingNumber : null;

        $nextStatus = $data['status'];
        if ($nextStatus !== 'cancelled') {
            if ($data['payment_status'] !== 'paid') {
                return back()->withErrors(['status' => 'Payment must be marked as paid before moving fulfillment status.']);
            }
        }

        if (in_array($nextStatus, ['processing', 'shipped', 'delivered'], true)) {
            if (!$addressVerifiedAt || !$stockConfirmedAt || !$packedAt || !$qualityCheckedAt) {
                return back()->withErrors(['status' => 'Address verification, stock confirmation, packing, and quality check are required before processing/shipping/delivery.']);
            }
        }

        if (in_array($nextStatus, ['shipped', 'delivered'], true)) {
            if (!$courierName || !$trackingNumber) {
                return back()->withErrors(['status' => 'Courier name and tracking number are required before shipping/delivery.']);
            }
        }

        if ($nextStatus === 'delivered' && !$deliveryConfirmedAt) {
            return back()->withErrors(['status' => 'Delivery confirmation is required before marking as delivered.']);
        }

        $order->update([
            'status' => $nextStatus,
            'payment_status' => $data['payment_status'],
            'address_verified_at' => $addressVerifiedAt,
            'stock_confirmed_at' => $stockConfirmedAt,
            'packed_at' => $packedAt,
            'quality_checked_at' => $qualityCheckedAt,
            'courier_name' => $courierName,
            'tracking_number' => $trackingNumber,
            'shipped_at' => $nextStatus === 'shipped' ? ($order->shipped_at ?? now()) : $order->shipped_at,
            'delivered_at' => $nextStatus === 'delivered' ? ($order->delivered_at ?? now()) : $order->delivered_at,
            'delivery_confirmed_at' => $deliveryConfirmedAt,
            'cancelled_at' => $nextStatus === 'cancelled' ? now() : null,
        ]);

        if (in_array($nextStatus, ['shipped', 'delivered'], true)) {
            Mail::to($order->email)->queue(new StoreOrderCustomerMail($order, 'shipping_update'));
        }

        return back()->with('success', 'Order updated.');
    }

    protected function checklistTimestamp(mixed $currentValue, bool $isChecked): ?\Illuminate\Support\Carbon
    {
        if (!$isChecked) {
            return null;
        }

        return $currentValue ?: now();
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
