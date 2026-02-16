@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Attendance Summary – {{ $classroom->name }}</h2>

    <div class="overflow-x-auto bg-white border rounded">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2 border-b">Student</th>
                    <th class="text-left px-4 py-2 border-b">Total Days</th>
                    <th class="text-left px-4 py-2 border-b">Present</th>
                    <th class="text-left px-4 py-2 border-b">Absent</th>
                    <th class="text-left px-4 py-2 border-b">Attendance %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary as $row)
                    <tr class="border-b">
                        <td class="px-4 py-2">
                            {{ $row['student']->first_name }}
                            {{ $row['student']->last_name }}
                        </td>
                        <td class="px-4 py-2">{{ $row['total'] }}</td>
                        <td class="px-4 py-2">{{ $row['present'] }}</td>
                        <td class="px-4 py-2">{{ $row['absent'] }}</td>
                        <td class="px-4 py-2">{{ $row['percentage'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
