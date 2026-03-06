@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10 space-y-6">
    <h1 class="text-3xl gc-heading">Online Exam</h1>

    <div class="gc-panel p-4 text-sm text-slate-600">
        Template: <strong>{{ $assignment->template->title }}</strong> |
        Class: <strong>{{ $assignment->classroom->name ?? 'N/A' }}</strong> |
        Term: <strong>{{ $assignment->term }}</strong> |
        Session: <strong>{{ $assignment->session }}</strong>
    </div>

    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">{{ session('error') }}</div>
    @endif

    @if($students->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No registered students are available for this class.</div>
    @else
        <form method="POST" action="{{ route('public.exams.submit', $assignment->exam_code) }}" class="space-y-6">
            @csrf

            <div class="gc-panel p-4">
                <label class="block text-sm font-medium text-slate-600 mb-1">Your Name</label>
                <select name="student_id" required>
                    <option value="">Select your name</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                    @endforeach
                </select>
            </div>

            @foreach($assignment->template->questions as $q)
                <div class="gc-panel p-4">
                    <div class="font-medium mb-3">
                        {{ $loop->iteration }}. {{ $q->question_text }}
                        <span class="text-xs text-slate-500">({{ $q->marks }} marks)</span>
                    </div>
                    @if($q->question_image_path)
                        <div class="mb-3">
                            <img src="{{ $q->question_image_path }}" alt="Question diagram" class="max-h-72 rounded border border-slate-300">
                        </div>
                    @endif
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

            <button type="submit" class="gc-btn-primary">Submit Exam</button>
        </form>
    @endif
</div>
@endsection
