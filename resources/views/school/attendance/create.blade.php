@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-3xl gc-heading">Mark Attendance</h2>
            <p class="text-sm text-slate-600">Class ID: {{ $classId }}</p>
        </div>
        <div class="text-sm text-slate-600">Students: {{ $students->count() }}</div>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('school.attendance.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="class_id" value="{{ $classId }}">

        <div class="gc-panel p-4">
            <label class="block text-sm font-medium text-slate-600 mb-1">Date</label>
            <input type="date" name="date" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>
                                <select name="attendance[{{ $student->id }}]">
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="gc-btn-primary">Save Attendance</button>
    </form>
</div>
@endsection
