@extends('layouts.app')

@section('content')
@php
    $orgName = $settings['organization_name']->value ?? 'Genchess Educational Services';
    $supportEmail = $settings['support_email']->value ?? '';
    $supportPhone = $settings['support_phone']->value ?? '';
    $currency = $payment->currency ?? ($settings['default_currency']->value ?? '');
@endphp
<div class="space-y-6 max-w-4xl">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Invoice</h2>
        <a href="{{ route('school.finance.index') }}" class="gc-btn-secondary">Back to Payments</a>
    </div>

    <div class="gc-panel p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <div class="text-2xl font-bold">{{ $orgName }}</div>
                <div class="text-sm text-slate-600">
                    @if($supportEmail) {{ $supportEmail }} @endif
                    @if($supportEmail && $supportPhone) - @endif
                    @if($supportPhone) {{ $supportPhone }} @endif
                </div>
            </div>
            <div class="text-sm text-slate-700">
                <div><strong>Term:</strong> {{ $payment->term }}</div>
                <div><strong>Session:</strong> {{ $payment->session }}</div>
                <div><strong>Date:</strong> {{ $payment->created_at?->format('Y-m-d') }}</div>
            </div>
        </div>

        <div class="mb-4 text-sm text-slate-700">
            <div><strong>School:</strong> {{ $payment->school->school_name ?? 'N/A' }}</div>
        </div>

        <div class="overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Students</th>
                        <th>Rate</th>
                        <th>Total Due</th>
                        <th>Amount Paid</th>
                        <th>Outstanding</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $outstanding = max(0, $payment->total_due - $payment->amount_paid);
                    @endphp
                    <tr>
                        <td>{{ $payment->student_count }}</td>
                        <td>{{ $payment->per_student_amount }} {{ $currency }}</td>
                        <td>{{ $payment->total_due }} {{ $currency }}</td>
                        <td>{{ $payment->amount_paid }} {{ $currency }}</td>
                        <td>{{ $outstanding }} {{ $currency }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-sm text-slate-700">
            <div><strong>Status:</strong> {{ ucfirst($payment->status) }}</div>
            @if($payment->first_due_date && $payment->second_due_date)
                <div><strong>Installments:</strong></div>
                <div>1st: {{ $payment->first_amount }} due {{ $payment->first_due_date->format('Y-m-d') }}</div>
                <div>2nd: {{ $payment->second_amount }} due {{ $payment->second_due_date->format('Y-m-d') }}</div>
            @endif
        </div>

        <div class="mt-6">
            <button onclick="window.print()" class="gc-btn-primary">Print Invoice</button>
        </div>
    </div>
</div>
@endsection
