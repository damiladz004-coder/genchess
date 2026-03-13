<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::query()
            ->where('user_id', $request->user()->id)
            ->latest('created_at');

        if ($request->filled('purpose')) {
            $query->where('purpose', $request->string('purpose'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $payments = $query->paginate(20)->withQueryString();

        return view('payments.history', [
            'payments' => $payments,
            'purposes' => Payment::purposes(),
        ]);
    }
}

