@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl gc-heading">{{ $topic->title }} - Quiz</h2>
            <p class="text-sm text-slate-600">Pass mark: {{ $topic->quiz->pass_mark }}%</p>
        </div>
        <a href="{{ route('instructor.training.show', $enrollment) }}" class="gc-btn-secondary">Back to Workspace</a>
    </div>

    @if(session('success'))
        <div class="rounded border border-green-200 bg-green-50 px-4 py-2 text-green-700">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded border border-red-200 bg-red-50 px-4 py-2 text-red-700">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('instructor.training.topics.quiz.submit', [$enrollment, $topic]) }}" class="gc-panel p-5 space-y-5">
        @csrf
        @foreach($topic->quiz->questions as $index => $question)
            <div class="border rounded p-4">
                <p class="font-semibold text-slate-800">{{ $index + 1 }}. {{ $question->question }}</p>
                @if(is_array($question->options) && count($question->options))
                    <div class="mt-2 space-y-1">
                        @foreach($question->options as $option)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" required>
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <input type="text" name="answers[{{ $question->id }}]" class="mt-2 w-full border rounded px-3 py-2" required>
                @endif
            </div>
        @endforeach
        <button type="submit" class="gc-btn-primary">Submit Quiz</button>
    </form>

    <div class="gc-panel p-5">
        <h3 class="font-semibold text-slate-800 mb-2">Recent Attempts</h3>
        @if($attempts->isEmpty())
            <p class="text-sm text-slate-600">No attempts yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="gc-table">
                    <thead>
                        <tr>
                            <th>Submitted</th>
                            <th>Score</th>
                            <th>Correct</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attempts as $attempt)
                            <tr>
                                <td>{{ $attempt->submitted_at?->format('Y-m-d H:i') }}</td>
                                <td>{{ $attempt->score }}%</td>
                                <td>{{ $attempt->correct_answers }} / {{ $attempt->total_questions }}</td>
                                <td>{{ $attempt->passed ? 'Passed' : 'Failed' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
