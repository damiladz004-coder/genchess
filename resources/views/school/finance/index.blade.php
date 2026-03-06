@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Payments</h2>
        <a href="{{ route('school.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
    </div>

    @php
        $settings = \App\Models\Setting::whereIn('key', ['organization_name', 'support_email', 'support_phone', 'default_currency'])
            ->get()
            ->keyBy('key');
        $orgName = $settings['organization_name']->value ?? 'Genchess Educational Services';
        $supportEmail = $settings['support_email']->value ?? '';
        $supportPhone = $settings['support_phone']->value ?? '';
        $defaultCurrency = $settings['default_currency']->value ?? '';
    @endphp

    <div class="gc-panel p-4">
        <div class="font-semibold">{{ $orgName }}</div>
        <div class="text-sm text-slate-600">
            @if($supportEmail) {{ $supportEmail }} @endif
            @if($supportEmail && $supportPhone) - @endif
            @if($supportPhone) {{ $supportPhone }} @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="gc-panel p-4">
            <h3 class="text-lg font-semibold mb-3">Student Count (For Invoice)</h3>
            <div class="text-sm text-slate-600 mb-2">
                Billing is based on enrolled students and agreed rate per term.
            </div>
            <div class="text-2xl font-bold">{{ $studentCount }}</div>
            @if($latestPricing)
                <div class="text-sm text-slate-700 mt-2">
                    Latest Rate: {{ $latestPricing->per_student_amount }} {{ $latestPricing->currency }}
                    ({{ $latestPricing->term }} - {{ $latestPricing->session }})
                </div>
            @endif
        </div>

        <div class="gc-panel p-4">
            <h3 class="text-lg font-semibold mb-3">Pricing Agreements</h3>
            @if($pricings->isEmpty())
                <p class="text-slate-600">No pricing agreements yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="gc-table min-w-full">
                        <thead>
                            <tr>
                                <th>Term</th>
                                <th>Session</th>
                                <th>Rate</th>
                                <th>Currency</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pricings as $pricing)
                                <tr>
                                    <td>{{ $pricing->term }}</td>
                                    <td>{{ $pricing->session }}</td>
                                    <td>{{ $pricing->per_student_amount }}</td>
                                    <td>{{ $pricing->currency }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="gc-panel p-4">
            <h3 class="text-lg font-semibold mb-3">Payment Schedule</h3>
            @if($payments->isEmpty())
                <p class="text-slate-600">No payment records yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="gc-table min-w-full">
                        <thead>
                            <tr>
                                <th>Term</th>
                                <th>Session</th>
                                <th>Total Due</th>
                                <th>Paid</th>
                                <th>Status</th>
                                <th>Installments</th>
                                <th>Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                @php
                                    $isOverdue = $payment->second_due_date
                                        ? ($payment->second_due_date->isPast() && $payment->amount_paid < $payment->total_due)
                                        : false;
                                    $statusClass = $payment->status === 'paid'
                                        ? 'text-emerald-700'
                                        : ($payment->status === 'partial' ? 'text-amber-700' : 'text-slate-700');
                                @endphp
                                <tr>
                                    <td>{{ $payment->term }}</td>
                                    <td>{{ $payment->session }}</td>
                                    <td>{{ $payment->total_due }}</td>
                                    <td>{{ $payment->amount_paid }}</td>
                                    <td>
                                        <span class="{{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                                        @if($isOverdue)
                                            <span class="text-rose-600 ml-2">(Overdue)</span>
                                        @endif
                                    </td>
                                    <td class="text-sm text-slate-600">
                                        @if($payment->first_due_date && $payment->second_due_date)
                                            1st: {{ $payment->first_amount }} due {{ $payment->first_due_date->format('Y-m-d') }}<br>
                                            2nd: {{ $payment->second_amount }} due {{ $payment->second_due_date->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a class="text-brand-700 underline" href="{{ route('school.finance.invoice', $payment) }}">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
