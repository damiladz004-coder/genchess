<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto">
        <h1 class="text-3xl gc-heading">Inventory Logs</h1>

        <div class="gc-panel p-4 overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Action</th>
                        <th>Qty</th>
                        <th>Before</th>
                        <th>After</th>
                        <th>Order</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                        <td>{{ $log->product->name ?? 'N/A' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->quantity }}</td>
                        <td>{{ $log->before_stock }}</td>
                        <td>{{ $log->after_stock }}</td>
                        <td>{{ $log->order->order_number ?? '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $logs->links() }}</div>
        </div>
    </div>
</x-app-layout>
