@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Exam Assignments</h2>
        <a href="{{ route('school.exams.assignments.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded">
            Assign Exam
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($assignments->isEmpty())
        <p>No exam assignments yet.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Template</th>
                        <th class="text-left px-4 py-2 border-b">Class</th>
                        <th class="text-left px-4 py-2 border-b">Term</th>
                        <th class="text-left px-4 py-2 border-b">Session</th>
                        <th class="text-left px-4 py-2 border-b">Mode</th>
                        <th class="text-left px-4 py-2 border-b">Date</th>
                        <th class="text-left px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $assignment->template->title ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $assignment->classroom->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $assignment->term }}</td>
                            <td class="px-4 py-2">{{ $assignment->session }}</td>
                            <td class="px-4 py-2">{{ ucfirst($assignment->mode) }}</td>
                            <td class="px-4 py-2">{{ $assignment->exam_date ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <a class="text-blue-600 underline mr-3" href="{{ route('school.exams.assignments.results', $assignment) }}">Results</a>
                                @if($assignment->mode === 'online')
                                    <a class="text-blue-600 underline" href="{{ route('school.exams.assignments.take', $assignment) }}">Start</a>
                                @else
                                    <a class="text-blue-600 underline" href="{{ route('school.exams.assignments.print', $assignment) }}">Print Sheet</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
