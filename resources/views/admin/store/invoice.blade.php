<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; margin: 2rem; }
        .header { display: flex; justify-content: space-between; margin-bottom: 1.5rem; }
        .title { font-size: 1.5rem; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #e5e7eb; padding: 0.5rem; text-align: left; }
        th { background: #f8fafc; }
        .totals { margin-top: 1rem; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="title">Genchess Store Invoice</div>
            <div>Invoice for: {{ $order->order_number }}</div>
        </div>
        <div>
            <div>Date: {{ now()->format('Y-m-d') }}</div>
            <div>Status: {{ ucfirst($order->status) }}</div>
        </div>
    </div>

    <div>
        <strong>Customer:</strong> {{ $order->customer_name }}<br>
        <strong>Email:</strong> {{ $order->email }}<br>
        <strong>Phone:</strong> {{ $order->phone }}<br>
        <strong>Address:</strong> {{ $order->delivery_address }}, {{ $order->state }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>NGN {{ number_format($item->unit_price_kobo / 100, 2) }}</td>
                    <td>NGN {{ number_format($item->total_price_kobo / 100, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div>Subtotal: NGN {{ number_format($order->subtotal_kobo / 100, 2) }}</div>
        <div>Delivery: NGN {{ number_format($order->delivery_fee_kobo / 100, 2) }}</div>
        <div><strong>Total: NGN {{ number_format($order->total_kobo / 100, 2) }}</strong></div>
    </div>
</body>
</html>

