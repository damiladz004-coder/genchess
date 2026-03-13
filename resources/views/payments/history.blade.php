@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Payment History</h2>
    </div>

    <form method="GET" class="gc-panel p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        <select name="purpose" class="border rounded px-3 py-2">
            <option value="">All services</option>
            @foreach($purposes as $purpose)
                <option value="{{ $purpose }}" @selected(request('purpose') === $purpose)>{{ ucfirst($purpose) }}</option>
            @endforeach
        </select>

        <select name="status" class="border rounded px-3 py-2">
            <option value="">All statuses</option>
            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
            <option value="paid" @selected(request('status') === 'paid')>Paid</option>
            <option value="failed" @selected(request('status') === 'failed')>Failed</option>
        </select>

        <button class="gc-btn-primary" type="submit">Filter</button>
    </form>

    <div class="gc-panel p-4">
        @if($payments->isEmpty())
            <p class="text-slate-600">No payments found.</p>
        @else
            <div class="overflow-x-auto">
                <table class="gc-table min-w-full">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Purpose</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->reference }}</td>
                                <td>{{ ucfirst($payment->purpose) }}</td>
                                <td>NGN {{ number_format(((int) $payment->amount) / 100, 2) }}</td>
                                <td>{{ ucfirst($payment->status) }}</td>
                                <td>{{ $payment->created_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

