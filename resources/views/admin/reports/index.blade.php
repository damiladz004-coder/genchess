@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Reports</h2>
        <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
    </div>

    <form method="GET" class="gc-panel p-4">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">School</label>
                <select name="school_id" class="w-full">
                    <option value="">All schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Term</label>
                <select name="term" class="w-full">
                    <option value="">All terms</option>
                    @foreach($terms as $term)
                        <option value="{{ $term }}" @selected(request('term') == $term)>
                            {{ $term }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-3">
            <button type="submit" class="gc-btn-primary">Filter</button>
            <a href="{{ route('admin.reports.index') }}" class="gc-btn-secondary">Reset</a>
            <a href="{{ route('admin.reports.export.all', request()->query()) }}" class="gc-btn-secondary">
                Export All
            </a>
        </div>
    </form>

    <div class="grid grid-cols-1 gap-6">
        <div class="gc-panel p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Students per School</h3>
                <a href="{{ route('admin.reports.export.students', request()->query()) }}" class="gc-btn-secondary text-xs px-3 py-1.5">Export CSV</a>
            </div>
            <div class="overflow-x-auto">
                <table class="gc-table min-w-full">
                    <thead>
                        <tr>
                            <th>School</th>
                            <th>Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentsPerSchool as $school)
                            <tr>
                                <td>{{ $school->school_name }}</td>
                                <td>{{ $school->students_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="gc-panel p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Payments per School</h3>
                <a href="{{ route('admin.reports.export.payments', request()->query()) }}" class="gc-btn-secondary text-xs px-3 py-1.5">Export CSV</a>
            </div>
            <div class="overflow-x-auto">
                <table class="gc-table min-w-full">
                    <thead>
                        <tr>
                            <th>School</th>
                            <th>Total Due</th>
                            <th>Total Paid</th>
                            <th>Outstanding</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentsPerSchool as $school)
                            @php
                                $payment = $paymentsPerSchool[$school->id] ?? null;
                                $due = $payment->total_due ?? 0;
                                $paid = $payment->total_paid ?? 0;
                                $outstanding = max(0, $due - $paid);
                            @endphp
                            <tr>
                                <td>{{ $school->school_name }}</td>
                                <td>{{ $due }}</td>
                                <td>{{ $paid }}</td>
                                <td>{{ $outstanding }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="gc-panel p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Instructor Workload</h3>
                <a href="{{ route('admin.reports.export.workload', request()->query()) }}" class="gc-btn-secondary text-xs px-3 py-1.5">Export CSV</a>
            </div>
            <div class="overflow-x-auto">
                <table class="gc-table min-w-full">
                    <thead>
                        <tr>
                            <th>Instructor</th>
                            <th>Classes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instructorWorkload as $instructor)
                            <tr>
                                <td>{{ $instructor->name }}</td>
                                <td>{{ $instructor->teaching_classes_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="gc-panel p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Schools by State</h3>
                <a href="{{ route('admin.reports.export.states', request()->query()) }}" class="gc-btn-secondary text-xs px-3 py-1.5">Export CSV</a>
            </div>
            <div class="overflow-x-auto">
                <table class="gc-table min-w-full">
                    <thead>
                        <tr>
                            <th>State</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schoolsByState as $state => $count)
                            <tr>
                                <td>{{ $state }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
