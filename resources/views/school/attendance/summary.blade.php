@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl gc-heading">Attendance Summary - {{ $classroom->name }}</h2>

    <div class="gc-panel overflow-x-auto">
        <table class="gc-table min-w-full">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Total Days</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Attendance %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary as $row)
                    <tr>
                        <td>{{ $row['student']->first_name }} {{ $row['student']->last_name }}</td>
                        <td>{{ $row['total'] }}</td>
                        <td>{{ $row['present'] }}</td>
                        <td>{{ $row['absent'] }}</td>
                        <td>{{ $row['percentage'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
