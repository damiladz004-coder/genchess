@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Online Exam</h2>

    <div class="mb-4 text-sm text-gray-600">
        Template: <strong>{{ $assignment->template->title }}</strong> ·
        Class: <strong>{{ $assignment->classroom->name ?? 'N/A' }}</strong> ·
        Term: <strong>{{ $assignment->term }}</strong> ·
        Session: <strong>{{ $assignment->session }}</strong>
    </div>

    @if($students->isEmpty())
        <p>No students in this class.</p>
    @else
        <form method="POST" action="{{ route('school.exams.assignments.submit', $assignment) }}" class="space-y-6">
            @csrf

            <div>
                <label class="block font-medium mb-1">Select Student</label>
                <select name="student_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                    @endforeach
                </select>
            </div>

            @foreach($assignment->template->questions as $q)
                <div class="bg-white border rounded p-4">
                    <div class="font-medium mb-3">
                        {{ $loop->iteration }}. {{ $q->question_text }}
                        <span class="text-xs text-gray-500">({{ $q->marks }} marks)</span>
                    </div>
                    <div class="space-y-2">
                        @foreach($q->options as $opt)
                            <label class="flex items-center gap-2">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt->id }}">
                                <span>{{ $opt->option_text }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">Submit Exam</button>
        </form>
    @endif
</div>
@endsection
