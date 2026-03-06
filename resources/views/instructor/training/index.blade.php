@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">My Training</h2>
        <a href="{{ route('instructor.dashboard') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if($enrollments->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No training enrollments yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Cohort</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Certificate</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollments as $enrollment)
                        <tr>
                            <td>Genchess Certified Chess Instructor Program (GCCIP)</td>
                            <td>{{ $enrollment->cohort->name ?? 'N/A' }}</td>
                            <td>
                                {{ $enrollment->cohort->start_date?->format('Y-m-d') ?? '-' }}
                                to
                                {{ $enrollment->cohort->end_date?->format('Y-m-d') ?? '-' }}
                            </td>
                            <td>{{ ucfirst($enrollment->status) }}</td>
                            <td>
                                @if($enrollment->isPaid())
                                    <a href="{{ route('instructor.training.show', $enrollment) }}" class="text-brand-700 font-semibold underline">Open Workspace</a>
                                @else
                                    <a href="{{ route('training.checkout') }}" class="text-amber-700 font-semibold underline">Complete Payment</a>
                                @endif
                            </td>
                            <td>
                                @if($enrollment->certification)
                                    <span class="text-emerald-700">Issued</span><br>
                                    <span class="text-xs text-slate-500">{{ $enrollment->certification->certificate_code }}</span>
                                @else
                                    <span class="text-slate-500">Not issued</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm {{ $enrollment->payment_status === 'paid' ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ ucfirst($enrollment->payment_status ?? 'pending') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
