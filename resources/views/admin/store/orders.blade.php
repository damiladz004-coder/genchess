<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Store Orders</h1>
            <div class="text-sm text-slate-600">Revenue (Paid): NGN {{ number_format($revenueKobo / 100, 2) }}</div>
        </div>

        <div class="bg-white border rounded p-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left">Order</th><th class="text-left">Customer</th><th class="text-left">Total</th>
                        <th class="text-left">Payment</th><th class="text-left">Status</th><th class="text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-t">
                            <td>{{ $order->order_number }}<br><span class="text-xs">{{ $order->created_at?->format('Y-m-d H:i') }}</span></td>
                            <td>{{ $order->customer_name }}<br><span class="text-xs">{{ $order->email }}</span></td>
                            <td>NGN {{ number_format($order->total_kobo / 100, 2) }}</td>
                            <td>{{ $order->payment_method }} / {{ $order->payment_status }}</td>
                            <td>{{ $order->status }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.store.orders.update-status', $order) }}" class="flex gap-2 items-center">
                                    @csrf
                                    @method('PATCH')
                                    <select name="payment_status" class="border px-2 py-1">
                                        <option value="pending" @selected($order->payment_status==='pending')>pending</option>
                                        <option value="paid" @selected($order->payment_status==='paid')>paid</option>
                                        <option value="failed" @selected($order->payment_status==='failed')>failed</option>
                                    </select>
                                    <select name="status" class="border px-2 py-1">
                                        @foreach(['pending','paid','processing','shipped','delivered','cancelled'] as $status)
                                            <option value="{{ $status }}" @selected($order->status===$status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <button class="text-blue-600 underline text-xs">Save</button>
                                </form>
                                <a class="text-xs text-slate-700 underline" target="_blank" href="{{ route('admin.store.orders.invoice', $order) }}">Invoice</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $orders->links() }}</div>
        </div>
    </div>
</x-app-layout>
