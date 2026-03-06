<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-3xl gc-heading">Store Orders</h1>
            <div class="text-sm text-slate-600">Revenue (Paid): NGN {{ number_format($revenueKobo / 100, 2) }}</div>
        </div>

        <div class="gc-panel p-4 overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}<br><span class="text-xs">{{ $order->created_at?->format('Y-m-d H:i') }}</span></td>
                            <td>
                                {{ $order->customer_name }}<br>
                                <span class="text-xs">{{ $order->email }}</span><br>
                                <span class="text-xs">{{ $order->phone }}</span><br>
                                <span class="text-xs">{{ $order->state }} - {{ $order->delivery_address }}</span>
                            </td>
                            <td>
                                <span class="text-xs block">Subtotal: NGN {{ number_format($order->subtotal_kobo / 100, 2) }}</span>
                                <span class="text-xs block">Delivery: NGN {{ number_format(($order->delivery_fee ?? (($order->delivery_fee_kobo ?? 0) / 100)), 2) }}</span>
                                <span class="font-semibold">Total: NGN {{ number_format($order->total_kobo / 100, 2) }}</span>
                            </td>
                            <td>{{ $order->payment_method }} / {{ $order->payment_status }}</td>
                            <td>{{ $order->status }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.store.orders.update-status', $order) }}" class="space-y-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex gap-2 items-center">
                                        <select name="payment_status">
                                            <option value="pending" @selected($order->payment_status==='pending')>pending</option>
                                            <option value="paid" @selected($order->payment_status==='paid')>paid</option>
                                            <option value="failed" @selected($order->payment_status==='failed')>failed</option>
                                        </select>
                                        <select name="status">
                                            @foreach(['pending','paid','processing','shipped','delivered','cancelled'] as $status)
                                                <option value="{{ $status }}" @selected($order->status===$status)>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-2 gap-1 text-xs">
                                        <input type="hidden" name="address_verified" value="0">
                                        <label><input type="checkbox" name="address_verified" value="1" @checked($order->address_verified_at)> Address verified</label>
                                        <input type="hidden" name="stock_confirmed" value="0">
                                        <label><input type="checkbox" name="stock_confirmed" value="1" @checked($order->stock_confirmed_at)> Stock confirmed</label>
                                        <input type="hidden" name="packed" value="0">
                                        <label><input type="checkbox" name="packed" value="1" @checked($order->packed_at)> Packed</label>
                                        <input type="hidden" name="quality_checked" value="0">
                                        <label><input type="checkbox" name="quality_checked" value="1" @checked($order->quality_checked_at)> Quality checked</label>
                                        <input type="hidden" name="delivery_confirmed" value="0">
                                        <label><input type="checkbox" name="delivery_confirmed" value="1" @checked($order->delivery_confirmed_at)> Delivery confirmed</label>
                                    </div>

                                    <input name="courier_name" value="{{ $order->courier_name }}" class="w-full" placeholder="Courier name">
                                    <input name="tracking_number" value="{{ $order->tracking_number }}" class="w-full" placeholder="Tracking number">

                                    <button class="gc-btn-secondary text-xs px-3 py-1.5">Save</button>
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
