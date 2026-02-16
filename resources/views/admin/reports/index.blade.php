@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Reports</h2>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 underline">Back to Dashboard</a>
    </div>

    <form method="GET" class="mb-4 bg-white border rounded p-4">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">School</label>
                <select name="school_id" class="w-full border rounded px-3 py-2">
                    <option value="">All schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Term</label>
                <select name="term" class="w-full border rounded px-3 py-2">
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
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">Filter</button>
            <a href="{{ route('admin.reports.index') }}" class="text-gray-700 underline">Reset</a>
            <a href="{{ route('admin.reports.export.all', request()->query()) }}" class="bg-gray-900 text-white px-4 py-2 rounded">
                Export All
            </a>
        </div>
    </form>

    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white border rounded p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold">Students per School</h3>
                <a href="{{ route('admin.reports.export.students', request()->query()) }}" class="text-sm text-blue-600 underline">Export CSV</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-2 py-1 border-b">School</th>
                            <th class="text-left px-2 py-1 border-b">Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentsPerSchool as $school)
                            <tr class="border-b">
                                <td class="px-2 py-1">{{ $school->school_name }}</td>
                                <td class="px-2 py-1">{{ $school->students_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border rounded p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold">Payments per School</h3>
                <a href="{{ route('admin.reports.export.payments', request()->query()) }}" class="text-sm text-blue-600 underline">Export CSV</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-2 py-1 border-b">School</th>
                            <th class="text-left px-2 py-1 border-b">Total Due</th>
                            <th class="text-left px-2 py-1 border-b">Total Paid</th>
                            <th class="text-left px-2 py-1 border-b">Outstanding</th>
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
                            <tr class="border-b">
                                <td class="px-2 py-1">{{ $school->school_name }}</td>
                                <td class="px-2 py-1">{{ $due }}</td>
                                <td class="px-2 py-1">{{ $paid }}</td>
                                <td class="px-2 py-1">{{ $outstanding }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border rounded p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold">Instructor Workload</h3>
                <a href="{{ route('admin.reports.export.workload', request()->query()) }}" class="text-sm text-blue-600 underline">Export CSV</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-2 py-1 border-b">Instructor</th>
                            <th class="text-left px-2 py-1 border-b">Classes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instructorWorkload as $instructor)
                            <tr class="border-b">
                                <td class="px-2 py-1">{{ $instructor->name }}</td>
                                <td class="px-2 py-1">{{ $instructor->teaching_classes_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border rounded p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold">Schools by State</h3>
                <a href="{{ route('admin.reports.export.states', request()->query()) }}" class="text-sm text-blue-600 underline">Export CSV</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-2 py-1 border-b">State</th>
                            <th class="text-left px-2 py-1 border-b">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schoolsByState as $state => $count)
                            <tr class="border-b">
                                <td class="px-2 py-1">{{ $state }}</td>
                                <td class="px-2 py-1">{{ $count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
