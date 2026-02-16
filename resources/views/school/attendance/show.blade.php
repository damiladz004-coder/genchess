@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Attendance – {{ $classroom->name }}</h2>

    @forelse($attendances as $date => $records)
        <div class="mb-6">
            <h4 class="font-semibold mb-2">
                {{ \Carbon\Carbon::parse($date)->toFormattedDateString() }}
            </h4>

            <div class="overflow-x-auto bg-white border rounded">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-2 border-b">Student</th>
                            <th class="text-left px-4 py-2 border-b">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $attendance)
                            <tr class="border-b">
                                <td class="px-4 py-2">
                                    {{ $attendance->student->first_name }}
                                    {{ $attendance->student->last_name }}
                                </td>
                                <td class="px-4 py-2">{{ ucfirst($attendance->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <p class="text-gray-600">No attendance records found.</p>
    @endforelse
</div>
@endsection
