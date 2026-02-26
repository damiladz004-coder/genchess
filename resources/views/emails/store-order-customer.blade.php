<p>Hello {{ $order->customer_name }},</p>

@if($mailType === 'payment_confirmation')
<p>Your payment for order <strong>{{ $order->order_number }}</strong> has been confirmed.</p>
@elseif($mailType === 'shipping_update')
<p>Your order <strong>{{ $order->order_number }}</strong> has a shipping update.</p>
@else
<p>Thank you for your order. Your order number is <strong>{{ $order->order_number }}</strong>.</p>
@endif

<p>Total: <strong>NGN {{ number_format($order->total_kobo / 100, 2) }}</strong></p>
<p>Status: <strong>{{ ucfirst($order->status) }}</strong></p>
<p>Payment: <strong>{{ ucfirst($order->payment_status) }}</strong></p>

<p>Regards,<br>Genchess Store</p>

