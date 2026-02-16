@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl gc-heading">School Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="gc-panel p-4">
            <div class="text-sm text-slate-500">Total Classes</div>
            <div class="text-2xl font-bold text-brand-800">{{ $totalClasses }}</div>
        </div>
        <div class="gc-panel p-4">
            <div class="text-sm text-slate-500">Total Students</div>
            <div class="text-2xl font-bold text-brand-800">{{ $totalStudents }}</div>
        </div>
        <div class="gc-panel p-4">
            <div class="text-sm text-slate-500">Assigned Instructors</div>
            <div class="text-2xl font-bold text-indigo-700">{{ $assignedInstructorCount }}</div>
        </div>
        <div class="gc-panel p-4">
            <div class="text-sm text-slate-500">Outstanding Balance</div>
            <div class="text-2xl font-bold text-rose-700">{{ $outstandingBalance }}</div>
        </div>
    </div>

    @if(isset($payment))
        @php
            $isOverdue = $payment->second_due_date
                ? ($payment->second_due_date->isPast() && $payment->amount_paid < $payment->total_due)
                : false;
            $statusClass = $payment->status === 'paid'
                ? 'text-emerald-700'
                : ($payment->status === 'partial' ? 'text-amber-700' : 'text-slate-700');
        @endphp
        <div class="gc-panel p-4">
            <div class="font-semibold mb-1">Payment Schedule</div>
            <div class="text-sm text-slate-600 mb-1">
                Term: {{ $payment->term }} - Session: {{ $payment->session }}
            </div>
            <div class="text-sm">
                Status:
                <span class="{{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                @if($isOverdue)
                    <span class="text-rose-600 ml-2">(Overdue)</span>
                @endif
            </div>
            @if($payment->first_due_date && $payment->second_due_date)
                <div class="text-sm text-slate-700 mt-2">
                    1st Installment: {{ $payment->first_amount }} due {{ $payment->first_due_date->format('Y-m-d') }}<br>
                    2nd Installment: {{ $payment->second_amount }} due {{ $payment->second_due_date->format('Y-m-d') }}
                </div>
            @endif
            <div class="text-sm text-slate-600 mt-2">
                Total Due: {{ $payment->total_due }} - Paid: {{ $payment->amount_paid }}
            </div>
        </div>
    @endif

    <div class="gc-panel p-4">
        <div class="text-sm uppercase tracking-wide text-slate-500 mb-3">Quick Links</div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('school.profile.edit') }}" class="gc-btn-secondary">School Profile</a>
            <a href="{{ route('school.classes.index') }}" class="gc-btn-secondary">Manage Classes</a>
            <a href="{{ route('school.class-teachers.index') }}" class="gc-btn-secondary">Class Teachers</a>
            <a href="{{ route('school.class-teacher-feedback.index') }}" class="gc-btn-secondary">Class Teacher Feedback</a>
            <a href="{{ route('school.students.index') }}" class="gc-btn-secondary">Students</a>
            <a href="{{ route('school.instructors.index') }}" class="gc-btn-secondary">Chess Instructors</a>
            <a href="{{ route('school.attendance.report') }}" class="gc-btn-secondary">Attendance Report</a>
            <a href="{{ route('school.exams.index') }}" class="gc-btn-secondary">Exams & Results</a>
            <a href="{{ route('school.finance.index') }}" class="gc-btn-secondary">Payments</a>
        </div>
    </div>
</div>
@endsection
