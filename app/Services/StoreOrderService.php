<?php

namespace App\Services;

use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StoreOrderService
{
    public function __construct(
        protected DeliveryFeeService $deliveryFeeService
    ) {
    }

    public function createOrderFromCart(array $checkoutData, array $cartSummary, string $paymentMethod): Order
    {
        return DB::transaction(function () use ($checkoutData, $cartSummary, $paymentMethod): Order {
            $lines = $cartSummary['items'];
            if (count($lines) < 1) {
                throw new \RuntimeException('Cart is empty.');
            }

            $subtotal = 0;
            foreach ($lines as $line) {
                /** @var Product $product */
                $product = Product::lockForUpdate()->find($line['product']->id);
                if (!$product || $product->status !== 'active') {
                    throw new \RuntimeException('One or more products are no longer available.');
                }
                if ($line['quantity'] > $product->stock_quantity) {
                    throw new \RuntimeException("Insufficient stock for {$product->name}.");
                }
                $subtotal += ((int) $product->price_kobo) * (int) $line['quantity'];
            }

            $deliveryFee = isset($checkoutData['delivery_fee'])
                ? (int) $checkoutData['delivery_fee']
                : $this->deliveryFeeService->calculateDeliveryFee($checkoutData['state'] ?? '');
            $deliveryFee = max(0, $deliveryFee);
            $deliveryFeeKobo = $deliveryFee * 100;
            $total = $subtotal + $deliveryFeeKobo;

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => $this->generateOrderNumber(),
                'customer_name' => $checkoutData['customer_name'],
                'phone' => $checkoutData['phone'],
                'email' => $checkoutData['email'],
                'delivery_address' => $checkoutData['delivery_address'],
                'state' => $checkoutData['state'],
                'order_type' => $checkoutData['order_type'],
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'status' => 'pending',
                'subtotal_kobo' => $subtotal,
                'delivery_fee_kobo' => $deliveryFeeKobo,
                'delivery_fee' => $deliveryFee,
                'total_kobo' => $total,
                'currency' => 'NGN',
                'notes' => $checkoutData['notes'] ?? null,
            ]);

            foreach ($lines as $line) {
                $product = Product::find($line['product']->id);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product?->id,
                    'product_name' => $product?->name ?? $line['product']->name,
                    'sku' => $product?->sku,
                    'quantity' => (int) $line['quantity'],
                    'unit_price_kobo' => (int) $product?->price_kobo,
                    'total_price_kobo' => ((int) $product?->price_kobo) * (int) $line['quantity'],
                    'options_json' => $line['options'] ?? [],
                ]);
            }

            return $order->fresh('items');
        });
    }

    public function markOrderPaid(Order $order, array $gatewayResponse = []): Order
    {
        return DB::transaction(function () use ($order, $gatewayResponse): Order {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($lockedOrder->payment_status === 'paid') {
                return $lockedOrder;
            }

            $lockedOrder->update([
                'payment_status' => 'paid',
                'status' => $lockedOrder->status === 'pending' ? 'paid' : $lockedOrder->status,
                'paid_at' => now(),
                'paystack_response' => $gatewayResponse ?: $lockedOrder->paystack_response,
            ]);

            $this->reduceStockForOrder($lockedOrder);

            return $lockedOrder->fresh('items');
        });
    }

    public function reduceStockForOrder(Order $order): void
    {
        foreach ($order->items as $item) {
            if (!$item->product_id) {
                continue;
            }

            $alreadyLogged = InventoryLog::where('order_id', $order->id)
                ->where('product_id', $item->product_id)
                ->where('action', 'reduce')
                ->exists();

            if ($alreadyLogged) {
                continue;
            }

            $product = Product::lockForUpdate()->find($item->product_id);
            if (!$product) {
                continue;
            }

            $before = (int) $product->stock_quantity;
            $after = max(0, $before - (int) $item->quantity);
            $product->update(['stock_quantity' => $after]);

            InventoryLog::create([
                'product_id' => $product->id,
                'order_id' => $order->id,
                'created_by' => auth()->id(),
                'action' => 'reduce',
                'quantity' => (int) $item->quantity,
                'before_stock' => $before,
                'after_stock' => $after,
                'note' => 'Stock reduced after paid order.',
            ]);
        }
    }

    protected function generateOrderNumber(): string
    {
        do {
            $number = 'GC-ORD-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
