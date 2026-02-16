@extends('layouts.app')

@section('content')
@php
    $orgName = $settings['organization_name']->value ?? 'Genchess';
    $supportEmail = $settings['support_email']->value ?? '';
    $supportPhone = $settings['support_phone']->value ?? '';
    $currency = $payment->currency ?? ($settings['default_currency']->value ?? '');
@endphp
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Invoice</h2>
        <a href="{{ route('school.finance.index') }}" class="text-blue-600 underline">Back to Payments</a>
    </div>

    <div class="bg-white border rounded p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <div class="text-2xl font-bold">{{ $orgName }}</div>
                <div class="text-sm text-gray-600">
                    @if($supportEmail) {{ $supportEmail }} @endif
                    @if($supportEmail && $supportPhone) · @endif
                    @if($supportPhone) {{ $supportPhone }} @endif
                </div>
            </div>
            <div class="text-sm text-gray-700">
                <div><strong>Term:</strong> {{ $payment->term }}</div>
                <div><strong>Session:</strong> {{ $payment->session }}</div>
                <div><strong>Date:</strong> {{ $payment->created_at?->format('Y-m-d') }}</div>
            </div>
        </div>

        <div class="mb-4 text-sm text-gray-700">
            <div><strong>School:</strong> {{ $payment->school->school_name ?? 'N/A' }}</div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Students</th>
                        <th class="text-left px-4 py-2 border-b">Rate</th>
                        <th class="text-left px-4 py-2 border-b">Total Due</th>
                        <th class="text-left px-4 py-2 border-b">Amount Paid</th>
                        <th class="text-left px-4 py-2 border-b">Outstanding</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $outstanding = max(0, $payment->total_due - $payment->amount_paid);
                    @endphp
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $payment->student_count }}</td>
                        <td class="px-4 py-2">{{ $payment->per_student_amount }} {{ $currency }}</td>
                        <td class="px-4 py-2">{{ $payment->total_due }} {{ $currency }}</td>
                        <td class="px-4 py-2">{{ $payment->amount_paid }} {{ $currency }}</td>
                        <td class="px-4 py-2">{{ $outstanding }} {{ $currency }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-sm text-gray-700">
            <div><strong>Status:</strong> {{ ucfirst($payment->status) }}</div>
            @if($payment->first_due_date && $payment->second_due_date)
                <div><strong>Installments:</strong></div>
                <div>1st: {{ $payment->first_amount }} due {{ $payment->first_due_date->format('Y-m-d') }}</div>
                <div>2nd: {{ $payment->second_amount }} due {{ $payment->second_due_date->format('Y-m-d') }}</div>
            @endif
        </div>

        <div class="mt-6">
            <button onclick="window.print()" class="bg-gray-900 text-white px-4 py-2 rounded">
                Print Invoice
            </button>
        </div>
    </div>
</div>
@endsection
