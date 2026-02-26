<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\StoreAdminAlertMail;
use App\Models\BulkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BulkOrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'delivery_location' => 'required|string|max:255',
            'products_needed' => 'required|string|max:5000',
            'quantity' => 'required|integer|min:1|max:100000',
            'additional_notes' => 'nullable|string|max:5000',
            'custom_logo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $logoPath = null;
        if ($request->hasFile('custom_logo')) {
            $logoPath = $request->file('custom_logo')->store('store/bulk-logos', 'public');
        }

        $bulkOrder = BulkOrder::create([
            'user_id' => auth()->id(),
            'organization_name' => $data['organization_name'],
            'contact_person' => $data['contact_person'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'delivery_location' => $data['delivery_location'],
            'items_json' => [
                'products_needed' => $data['products_needed'],
                'quantity' => (int) $data['quantity'],
                'custom_logo_path' => $logoPath,
            ],
            'additional_notes' => $data['additional_notes'] ?? null,
            'status' => 'pending',
        ]);

        Mail::to(config('mail.from.address'))->queue(new StoreAdminAlertMail(bulkOrder: $bulkOrder));

        return back()->with('success', 'Bulk quote request submitted. We will contact you shortly.');
    }
}

