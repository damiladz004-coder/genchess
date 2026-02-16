@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold">Mark Attendance</h2>
            <p class="text-sm text-gray-600">Class ID: {{ $classId }}</p>
        </div>
        <div class="text-sm text-gray-600">
            Students: {{ $students->count() }}
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-50 border border-green-200 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 text-red-700 bg-red-50 border border-red-200 px-4 py-2 rounded">
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

        <div>
            <label class="block text-sm font-medium">Date</label>
            <input type="date" name="date" value="{{ date('Y-m-d') }}"
                   class="border px-3 py-2 rounded" required>
        </div>

        <div class="overflow-x-auto bg-white border rounded">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Student</th>
                        <th class="text-left px-4 py-2 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td class="px-4 py-2">
                                <select name="attendance[{{ $student->id }}]"
                                        class="border px-2 py-1 rounded">
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Save Attendance
        </button>
    </form>
</div>
@endsection
