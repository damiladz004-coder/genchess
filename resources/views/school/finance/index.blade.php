@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">Payments</h2>
        <a href="{{ route('school.dashboard') }}" class="text-blue-600 underline">Back to Dashboard</a>
    </div>

    @php
        $settings = \App\Models\Setting::whereIn('key', ['organization_name', 'support_email', 'support_phone', 'default_currency'])
            ->get()
            ->keyBy('key');
        $orgName = $settings['organization_name']->value ?? 'Genchess';
        $supportEmail = $settings['support_email']->value ?? '';
        $supportPhone = $settings['support_phone']->value ?? '';
        $defaultCurrency = $settings['default_currency']->value ?? '';
    @endphp

    <div class="bg-white border rounded p-4 mb-6">
        <div class="font-semibold">{{ $orgName }}</div>
        <div class="text-sm text-gray-600">
            @if($supportEmail) {{ $supportEmail }} @endif
            @if($supportEmail && $supportPhone) · @endif
            @if($supportPhone) {{ $supportPhone }} @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white border rounded p-4">
            <h3 class="text-lg font-semibold mb-3">Student Count (For Invoice)</h3>
            <div class="text-sm text-gray-600 mb-2">
                Genchess bills the school based on enrolled students and agreed rate per term.
            </div>
            <div class="text-2xl font-bold">{{ $studentCount }}</div>
            @if($latestPricing)
                <div class="text-sm text-gray-700 mt-2">
                    Latest Rate: {{ $latestPricing->per_student_amount }} {{ $latestPricing->currency }}
                    ({{ $latestPricing->term }} · {{ $latestPricing->session }})
                </div>
            @endif
        </div>

        <div class="bg-white border rounded p-4">
            <h3 class="text-lg font-semibold mb-3">Pricing Agreements</h3>
            @if($pricings->isEmpty())
                <p class="text-gray-600">No pricing agreements yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-2 border-b">Term</th>
                                <th class="text-left px-4 py-2 border-b">Session</th>
                                <th class="text-left px-4 py-2 border-b">Rate</th>
                                <th class="text-left px-4 py-2 border-b">Currency</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pricings as $pricing)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $pricing->term }}</td>
                                    <td class="px-4 py-2">{{ $pricing->session }}</td>
                                    <td class="px-4 py-2">{{ $pricing->per_student_amount }}</td>
                                    <td class="px-4 py-2">{{ $pricing->currency }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="bg-white border rounded p-4">
            <h3 class="text-lg font-semibold mb-3">Payment Schedule</h3>
            @if($payments->isEmpty())
                <p class="text-gray-600">No payment records yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-2 border-b">Term</th>
                                <th class="text-left px-4 py-2 border-b">Session</th>
                                <th class="text-left px-4 py-2 border-b">Total Due</th>
                                <th class="text-left px-4 py-2 border-b">Paid</th>
                                <th class="text-left px-4 py-2 border-b">Status</th>
                                <th class="text-left px-4 py-2 border-b">Installments</th>
                                <th class="text-left px-4 py-2 border-b">Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                @php
                                    $isOverdue = $payment->second_due_date
                                        ? ($payment->second_due_date->isPast() && $payment->amount_paid < $payment->total_due)
                                        : false;
                                    $statusClass = $payment->status === 'paid'
                                        ? 'text-green-700'
                                        : ($payment->status === 'partial' ? 'text-yellow-700' : 'text-gray-700');
                                @endphp
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $payment->term }}</td>
                                    <td class="px-4 py-2">{{ $payment->session }}</td>
                                    <td class="px-4 py-2">{{ $payment->total_due }}</td>
                                    <td class="px-4 py-2">{{ $payment->amount_paid }}</td>
                                    <td class="px-4 py-2">
                                        <span class="{{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                                        @if($isOverdue)
                                            <span class="text-red-600 ml-2">(Overdue)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-600">
                                        @if($payment->first_due_date && $payment->second_due_date)
                                            1st: {{ $payment->first_amount }} due {{ $payment->first_due_date->format('Y-m-d') }}<br>
                                            2nd: {{ $payment->second_amount }} due {{ $payment->second_due_date->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <a class="text-blue-600 underline" href="{{ route('school.finance.invoice', $payment) }}">
                                            View
                                        </a>
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
