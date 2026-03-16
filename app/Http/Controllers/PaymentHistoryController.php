<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        if (!Schema::hasTable('payments')) {
            $payments = new LengthAwarePaginator([], 0, 20, 1, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);

            return view('payments.history', [
                'payments' => $payments,
                'purposes' => Payment::purposes(),
            ]);
        }

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
