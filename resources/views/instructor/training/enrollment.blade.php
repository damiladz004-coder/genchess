@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl gc-heading">{{ $enrollment->cohort->course->title ?? 'Training' }}</h2>
            <p class="text-sm text-slate-600">Cohort: {{ $enrollment->cohort->name ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('instructor.training.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="rounded border border-green-200 bg-green-50 px-4 py-2 text-green-700">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="rounded border border-red-200 bg-red-50 px-4 py-2 text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="gc-panel p-5">
        <h3 class="text-xl font-semibold text-slate-800">Certification Progress</h3>
        <div class="mt-3 grid sm:grid-cols-2 lg:grid-cols-4 gap-3 text-sm">
            <div class="rounded border p-3 {{ $enrollment->quizzes_completed ? 'border-green-300 bg-green-50' : 'border-slate-200' }}">
                Quizzes: {{ $enrollment->quizzes_completed ? 'Complete' : 'Pending' }}
            </div>
            <div class="rounded border p-3 {{ $enrollment->assignments_completed ? 'border-green-300 bg-green-50' : 'border-slate-200' }}">
                Assignments: {{ $enrollment->assignments_completed ? 'Complete' : 'Pending' }}
            </div>
            <div class="rounded border p-3 {{ $enrollment->teaching_practice_completed ? 'border-green-300 bg-green-50' : 'border-slate-200' }}">
                Teaching Practice: {{ $enrollment->teaching_practice_completed ? 'Complete' : 'Pending' }}
            </div>
            <div class="rounded border p-3 {{ $enrollment->mentor_approved ? 'border-green-300 bg-green-50' : 'border-slate-200' }}">
                Mentor Approval: {{ $enrollment->mentor_approved ? 'Approved' : 'Pending' }}
            </div>
        </div>
    </div>

    @foreach($enrollment->cohort->course->modules as $module)
        <div class="gc-panel p-5 space-y-3">
            <h3 class="text-xl font-semibold text-slate-800">{{ $module->title }}</h3>
            @if($module->goal)
                <p class="text-sm text-slate-600">{{ $module->goal }}</p>
            @endif

            @foreach($module->topics as $topic)
                @php
                    $progress = $progressByTopic->get($topic->id);
                    $submissions = $submissionsByTopic->get($topic->id, collect());
                @endphp
                <div class="rounded border border-slate-200 p-4 bg-slate-50/60">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h4 class="font-semibold text-slate-800">{{ $topic->title }}</h4>
                            <p class="text-xs text-slate-500">
                                Pass mark: {{ $topic->quiz->pass_mark ?? 70 }}% |
                                Quiz score: {{ $progress?->quiz_score ?? '-' }} |
                                Assignment: {{ ucfirst(str_replace('_', ' ', $progress?->assignment_status ?? 'not_started')) }}
                            </p>
                            @if($topic->quiz)
                                <a href="{{ route('instructor.training.topics.quiz.show', [$enrollment, $topic]) }}" class="text-xs text-brand-700 underline">Take Quiz</a>
                            @endif
                        </div>
                        <span class="text-xs px-2 py-1 rounded {{ ($progress?->completed_at) ? 'bg-green-100 text-green-800' : 'bg-slate-200 text-slate-700' }}">
                            {{ ($progress?->completed_at) ? 'Completed' : 'In Progress' }}
                        </span>
                    </div>

                    @if($submissions->isNotEmpty())
                        <div class="mt-2 text-xs text-slate-600 space-y-1">
                            @foreach($submissions as $submission)
                                <div>
                                    Submission status: {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                    @if($submission->mentor_feedback)
                                        | Feedback: {{ $submission->mentor_feedback }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('instructor.training.topics.submit', [$enrollment, $topic]) }}" class="mt-3 grid md:grid-cols-4 gap-2">
                        @csrf
                        <input class="border px-3 py-2 md:col-span-3" type="text" name="submission_text" placeholder="Submission summary / notes">
                        <input class="border px-3 py-2" type="url" name="submission_url" placeholder="Submission URL">
                        <button class="gc-btn-primary md:col-span-4 justify-center" type="submit">Submit Topic Work</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="gc-panel p-5">
        <h3 class="text-xl font-semibold text-slate-800">Capstone Submission</h3>
        <p class="text-sm text-slate-600 mt-1">Upload your 15-minute teaching video link for mentor review.</p>
        @if($enrollment->capstoneReview)
            <p class="text-xs text-slate-500 mt-2">
                Status: {{ ucfirst(str_replace('_', ' ', $enrollment->capstoneReview->status)) }}
                @if($enrollment->capstoneReview->mentor_feedback)
                    | Feedback: {{ $enrollment->capstoneReview->mentor_feedback }}
                @endif
            </p>
        @endif
        <form method="POST" action="{{ route('instructor.training.capstone.submit', $enrollment) }}" class="mt-3 flex flex-col md:flex-row gap-2">
            @csrf
            <input class="border px-3 py-2 flex-1" type="url" name="video_url" placeholder="https://..." required>
            <button class="gc-btn-primary justify-center" type="submit">Submit Capstone</button>
        </form>
    </div>
</div>
@endsection
