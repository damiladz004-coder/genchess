@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4 gap-3">
        <h2 class="text-2xl font-bold">Exams</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('school.exams.assignments.index') }}"
               class="bg-gray-900 text-white px-3 py-2 rounded">
                Exam Assignments
            </a>
            <a href="{{ route('school.exams.create') }}"
               class="bg-blue-600 text-white px-3 py-2 rounded">
                Create Exam
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-50 border border-green-200 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($exams->isEmpty())
        <p class="text-gray-600">No exams created yet.</p>
    @else
        <div class="overflow-x-auto bg-white border rounded">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Title</th>
                        <th class="text-left px-4 py-2 border-b">Class</th>
                        <th class="text-left px-4 py-2 border-b">Term</th>
                        <th class="text-left px-4 py-2 border-b">Session</th>
                        <th class="text-left px-4 py-2 border-b">Date</th>
                        <th class="text-left px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $exam)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $exam->title }}</td>
                            <td class="px-4 py-2">{{ $exam->classroom->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $exam->term }}</td>
                            <td class="px-4 py-2">{{ $exam->session }}</td>
                            <td class="px-4 py-2">{{ $exam->exam_date ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('school.exams.show', $exam) }}"
                                   class="text-blue-600 underline">
                                    Enter Results
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
