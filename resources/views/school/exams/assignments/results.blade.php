@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-xl font-bold">Exam Results Summary</h2>
            <p class="text-sm text-gray-600">
                {{ $assignment->template->title }} · {{ $assignment->classroom->name ?? 'N/A' }} · {{ $assignment->term }} · {{ $assignment->session }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('school.exams.assignments.index') }}" class="text-gray-700 underline">Back</a>
            <a href="{{ route('admin.exams.attempts.index', ['school_id' => $assignment->school_id, 'class_id' => $assignment->class_id]) }}"
               class="bg-gray-900 text-white px-3 py-2 rounded text-sm">
                Request Retake (Super Admin)
            </a>
        </div>
    </div>

    <div class="grid md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border rounded p-4">
            <div class="text-xs text-gray-500">Attempts</div>
            <div class="text-lg font-semibold">{{ $summary['attempts'] }}</div>
        </div>
        <div class="bg-white border rounded p-4">
            <div class="text-xs text-gray-500">Average</div>
            <div class="text-lg font-semibold">{{ $summary['average'] }}</div>
        </div>
        <div class="bg-white border rounded p-4">
            <div class="text-xs text-gray-500">Highest</div>
            <div class="text-lg font-semibold">{{ $summary['highest'] }}</div>
        </div>
        <div class="bg-white border rounded p-4">
            <div class="text-xs text-gray-500">Lowest</div>
            <div class="text-lg font-semibold">{{ $summary['lowest'] }}</div>
        </div>
    </div>

    @if($attempts->isEmpty())
        <p>No attempts yet.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Student</th>
                        <th class="text-left px-4 py-2 border-b">Score</th>
                        <th class="text-left px-4 py-2 border-b">Total Marks</th>
                        <th class="text-left px-4 py-2 border-b">Submitted</th>
                        <th class="text-left px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attempts as $attempt)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $attempt->student->first_name ?? '' }} {{ $attempt->student->last_name ?? '' }}</td>
                            <td class="px-4 py-2">{{ $attempt->score }}</td>
                            <td class="px-4 py-2">{{ $attempt->total_marks }}</td>
                            <td class="px-4 py-2">{{ optional($attempt->submitted_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span class="text-gray-500 text-sm">Reset via Super Admin</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
