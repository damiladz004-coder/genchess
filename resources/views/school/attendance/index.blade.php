@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold">Take Attendance</h2>
            <p class="text-sm text-gray-600">Class: {{ $class->name }}</p>
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

        <input type="hidden" name="class_id" value="{{ $class->id }}">

        <div class="flex items-center gap-4">
            <div>
                <label class="block text-sm font-medium">Date</label>
                <input type="date" name="date" value="{{ now()->toDateString() }}"
                       class="border px-3 py-2 rounded" required>
            </div>
            <div class="pt-6 flex gap-2">
                <button type="button" id="mark-all-present"
                        class="bg-green-600 text-white px-3 py-2 rounded">
                    Mark All Present
                </button>
                <button type="button" id="mark-all-absent"
                        class="bg-red-600 text-white px-3 py-2 rounded">
                    Mark All Absent
                </button>
            </div>
        </div>

        <div class="overflow-x-auto bg-white border rounded">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Student</th>
                        <th class="text-center px-4 py-2 border-b">Present</th>
                        <th class="text-center px-4 py-2 border-b">Absent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr class="border-b">
                        <td class="px-4 py-2">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </td>
                        <td class="text-center px-4 py-2">
                            <input type="radio"
                                   name="attendance[{{ $student->id }}]"
                                   value="present"
                                   required>
                        </td>
                        <td class="text-center px-4 py-2">
                            <input type="radio"
                                   name="attendance[{{ $student->id }}]"
                                   value="absent"
                                   required>
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

<script>
    document.getElementById('mark-all-present').addEventListener('click', function () {
        document.querySelectorAll('input[type="radio"][value="present"]').forEach(function (input) {
            input.checked = true;
        });
    });
    document.getElementById('mark-all-absent').addEventListener('click', function () {
        document.querySelectorAll('input[type="radio"][value="absent"]').forEach(function (input) {
            input.checked = true;
        });
    });
</script>
@endsection
