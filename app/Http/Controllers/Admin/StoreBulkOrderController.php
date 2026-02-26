<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BulkOrder;
use Illuminate\Http\Request;

class StoreBulkOrderController extends Controller
{
    public function index()
    {
        $bulkOrders = BulkOrder::latest('id')->paginate(30);

        return view('admin.store.bulk-orders', compact('bulkOrders'));
    }

    public function update(Request $request, BulkOrder $bulkOrder)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,quoted,invoiced,cancelled',
            'custom_price' => 'nullable|numeric|min:0',
            'invoice_number' => 'nullable|string|max:255',
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $bulkOrder->update([
            'status' => $data['status'],
            'custom_price_kobo' => isset($data['custom_price']) ? (int) round(((float) $data['custom_price']) * 100) : null,
            'invoice_number' => $data['invoice_number'] ?? null,
            'admin_notes' => $data['admin_notes'] ?? null,
        ]);

        return back()->with('success', 'Bulk order request updated.');
    }
}

