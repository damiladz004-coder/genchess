@if($bulkOrder)
<p>New bulk quote request received.</p>
<p><strong>Organization:</strong> {{ $bulkOrder->organization_name }}</p>
<p><strong>Contact:</strong> {{ $bulkOrder->contact_person }} ({{ $bulkOrder->email }}, {{ $bulkOrder->phone }})</p>
<p><strong>Delivery:</strong> {{ $bulkOrder->delivery_location }}</p>
@else
<p>New store order received.</p>
<p><strong>Order Number:</strong> {{ $order->order_number }}</p>
<p><strong>Customer:</strong> {{ $order->customer_name }} ({{ $order->email }})</p>
<p><strong>Total:</strong> NGN {{ number_format($order->total_kobo / 100, 2) }}</p>
<p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
@endif

