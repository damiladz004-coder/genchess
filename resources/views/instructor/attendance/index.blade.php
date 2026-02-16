@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl gc-heading">Take Attendance - {{ $classroom->name }}</h2>

    <form method="POST" action="{{ route('instructor.attendance.store', $classroom) }}" class="gc-panel p-4 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Date</label>
            <input
                type="date"
                name="date"
                value="{{ $date }}"
                onchange="window.location='{{ route('instructor.attendance.index', $classroom) }}?date=' + this.value"
            >
        </div>

        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Present</th>
                        <th>Absent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td class="font-medium text-slate-800">{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>
                            <input type="radio" name="attendance[{{ $student->id }}]" value="present"
                                   @checked(($existing[$student->id] ?? null) === 'present') required>
                        </td>
                        <td>
                            <input type="radio" name="attendance[{{ $student->id }}]" value="absent"
                                   @checked(($existing[$student->id] ?? null) === 'absent') required>
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
