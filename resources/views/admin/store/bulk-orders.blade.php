<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto">
        <h1 class="text-3xl gc-heading">Bulk Order Requests</h1>

        <div class="gc-panel p-4 overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Organization</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bulkOrders as $bulkOrder)
                    <tr>
                        <td>{{ $bulkOrder->organization_name }}</td>
                        <td>{{ $bulkOrder->contact_person }}<br><span class="text-xs">{{ $bulkOrder->email }}</span></td>
                        <td>{{ $bulkOrder->status }}</td>
                        <td>
                            <div class="text-xs text-slate-600">{{ $bulkOrder->delivery_location }}</div>
                            <div class="text-xs text-slate-600">{{ json_encode($bulkOrder->items_json) }}</div>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.store.bulk-orders.update', $bulkOrder) }}" class="space-y-1">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="w-full">
                                    @foreach(['pending','approved','quoted','invoiced','cancelled'] as $status)
                                        <option value="{{ $status }}" @selected($bulkOrder->status===$status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                                <input name="custom_price" class="w-full" placeholder="Custom price NGN" value="{{ $bulkOrder->custom_price_kobo ? $bulkOrder->custom_price_kobo / 100 : '' }}">
                                <input name="invoice_number" class="w-full" placeholder="Invoice number" value="{{ $bulkOrder->invoice_number }}">
                                <textarea name="admin_notes" class="w-full" rows="2" placeholder="Admin notes">{{ $bulkOrder->admin_notes }}</textarea>
                                <button class="gc-btn-secondary text-xs px-3 py-1.5">Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $bulkOrders->links() }}</div>
        </div>
    </div>
</x-app-layout>
