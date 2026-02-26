<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold">Bulk Order Requests</h1>

        <div class="bg-white border rounded p-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr><th class="text-left">Organization</th><th class="text-left">Contact</th><th class="text-left">Status</th><th class="text-left">Details</th><th class="text-left">Action</th></tr>
                </thead>
                <tbody>
                @foreach($bulkOrders as $bulkOrder)
                    <tr class="border-t">
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
                                <select name="status" class="border px-2 py-1 w-full">
                                    @foreach(['pending','approved','quoted','invoiced','cancelled'] as $status)
                                        <option value="{{ $status }}" @selected($bulkOrder->status===$status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                                <input name="custom_price" class="border px-2 py-1 w-full" placeholder="Custom price NGN" value="{{ $bulkOrder->custom_price_kobo ? $bulkOrder->custom_price_kobo / 100 : '' }}">
                                <input name="invoice_number" class="border px-2 py-1 w-full" placeholder="Invoice number" value="{{ $bulkOrder->invoice_number }}">
                                <textarea name="admin_notes" class="border px-2 py-1 w-full" rows="2" placeholder="Admin notes">{{ $bulkOrder->admin_notes }}</textarea>
                                <button class="text-blue-600 underline text-xs">Update</button>
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

