<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold">Inventory Logs</h1>

        <div class="bg-white border rounded p-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr><th class="text-left">Date</th><th class="text-left">Product</th><th class="text-left">Action</th><th class="text-left">Qty</th><th class="text-left">Before</th><th class="text-left">After</th><th class="text-left">Order</th></tr>
                </thead>
                <tbody>
                @foreach($logs as $log)
                    <tr class="border-t">
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

