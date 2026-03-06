@extends('layouts.app')

@section('content')
@php
    $toEmbedUrl = function (?string $url): ?string {
        if (!$url) {
            return null;
        }

        $parsed = parse_url($url);
        $host = strtolower($parsed['host'] ?? '');
        $path = $parsed['path'] ?? '';
        parse_str($parsed['query'] ?? '', $query);

        if (str_contains($host, 'youtube.com')) {
            $id = $query['v'] ?? null;
            return $id ? "https://www.youtube.com/embed/{$id}" : null;
        }

        if (str_contains($host, 'youtu.be')) {
            $id = ltrim($path, '/');
            return $id !== '' ? "https://www.youtube.com/embed/{$id}" : null;
        }

        if (str_contains($host, 'vimeo.com')) {
            $id = trim($path, '/');
            return $id !== '' ? "https://player.vimeo.com/video/{$id}" : null;
        }

        return null;
    };
@endphp
<div class="space-y-6" x-data="{ tab: 'lessons' }">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl gc-heading">Genchess Certified Chess Instructor Program (GCCIP)</h2>
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
        <h3 class="text-xl font-semibold text-slate-800">Progress Tracking</h3>
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

    <div class="gc-panel p-3 flex flex-wrap gap-2 text-sm">
        <button type="button" class="gc-btn-secondary px-3 py-1.5" :class="tab === 'lessons' ? 'ring-2 ring-slate-700' : ''" @click="tab='lessons'">Lessons</button>
        <button type="button" class="gc-btn-secondary px-3 py-1.5" :class="tab === 'assignments' ? 'ring-2 ring-slate-700' : ''" @click="tab='assignments'">Assignments</button>
        <button type="button" class="gc-btn-secondary px-3 py-1.5" :class="tab === 'assessments' ? 'ring-2 ring-slate-700' : ''" @click="tab='assessments'">Assessments</button>
        <button type="button" class="gc-btn-secondary px-3 py-1.5" :class="tab === 'discussion' ? 'ring-2 ring-slate-700' : ''" @click="tab='discussion'">Discussion</button>
        <button type="button" class="gc-btn-secondary px-3 py-1.5" :class="tab === 'live-classes' ? 'ring-2 ring-slate-700' : ''" @click="tab='live-classes'">Live Classes</button>
        <button type="button" class="gc-btn-secondary px-3 py-1.5" :class="tab === 'resources' ? 'ring-2 ring-slate-700' : ''" @click="tab='resources'">Resources</button>
    </div>

    <div x-show="tab === 'lessons'" class="space-y-6">
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
                        $lessonPrefix = '[Lesson: '.$topic->title.']';
                        $lessonDiscussions = $courseDiscussions->filter(fn ($d) => str_starts_with($d->message, $lessonPrefix));
                    @endphp
                    <div class="rounded border border-slate-200 p-4 bg-slate-50/60 space-y-3">
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
                                @if($topic->lesson_video_url)
                                    <span class="text-xs text-slate-400 mx-1">|</span>
                                    <a href="{{ $topic->lesson_video_url }}" target="_blank" rel="noopener" class="text-xs text-brand-700 underline">Watch Lesson Video</a>
                                @endif
                            </div>
                            <span class="text-xs px-2 py-1 rounded {{ ($progress?->completed_at) ? 'bg-green-100 text-green-800' : 'bg-slate-200 text-slate-700' }}">
                                {{ ($progress?->completed_at) ? 'Completed' : 'In Progress' }}
                            </span>
                        </div>

                        @if($topic->lesson_video_url)
                            @php $embedUrl = $toEmbedUrl($topic->lesson_video_url); @endphp
                            @if($embedUrl)
                                <div class="rounded border bg-white p-2">
                                    <iframe
                                        src="{{ $embedUrl }}"
                                        title="Lesson Video - {{ $topic->title }}"
                                        class="w-full aspect-video rounded"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen
                                    ></iframe>
                                </div>
                            @endif
                        @endif

                        @if($submissions->isNotEmpty())
                            <div class="text-xs text-slate-600 space-y-1">
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

                        <form method="POST" action="{{ route('instructor.training.topics.submit', [$enrollment, $topic]) }}" class="grid md:grid-cols-4 gap-2">
                            @csrf
                            <input class="border px-3 py-2 md:col-span-3" type="text" name="submission_text" placeholder="Submission summary / notes">
                            <input class="border px-3 py-2" type="url" name="submission_url" placeholder="Submission URL">
                            <button class="gc-btn-primary md:col-span-4 justify-center" type="submit">Submit Topic Work</button>
                        </form>

                        <div class="border-t pt-3">
                            <h5 class="text-sm font-semibold text-slate-700 mb-2">Lesson Discussion</h5>
                            @forelse($lessonDiscussions as $discussion)
                                <div class="mb-2 text-xs text-slate-700 bg-white border rounded p-2">
                                    <div class="font-semibold">{{ $discussion->user->name ?? 'User' }} <span class="text-slate-400 font-normal">· {{ $discussion->created_at?->diffForHumans() }}</span></div>
                                    <div>{{ preg_replace('/^\[Lesson:.*?\]\s*/', '', $discussion->message) }}</div>
                                    @if($discussion->replies->isNotEmpty())
                                        <div class="mt-1 pl-3 border-l space-y-1">
                                            @foreach($discussion->replies as $reply)
                                                <div><strong>{{ $reply->user->name ?? 'User' }}:</strong> {{ $reply->message }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 mb-2">No discussion yet for this lesson.</p>
                            @endforelse
                            <form method="POST" action="{{ route('instructor.training.discussions.store', $enrollment) }}" class="flex gap-2">
                                @csrf
                                <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                                <input class="border px-3 py-2 w-full text-xs" name="message" placeholder="Ask a question about this lesson..." required>
                                <button class="gc-btn-secondary text-xs px-3 py-1.5" type="submit">Post</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <div x-show="tab === 'assignments'" class="gc-panel p-5 space-y-3">
        <h3 class="text-xl font-semibold text-slate-800">Assignments</h3>
        @php
            $allAssignments = $enrollment->cohort->course->modules->flatMap(fn ($m) => $m->topics)->flatMap(fn ($t) => $t->assignments->map(fn ($a) => ['topic' => $t, 'assignment' => $a]));
        @endphp
        @forelse($allAssignments as $row)
            <div class="border rounded p-3">
                <div class="font-semibold">{{ $row['assignment']->title }}</div>
                <div class="text-xs text-slate-500">Topic: {{ $row['topic']->title }}</div>
                @if($row['assignment']->due_at)
                    <div class="text-xs text-amber-700">Due: {{ $row['assignment']->due_at->format('F j, Y g:i A') }}</div>
                @endif
                <div class="text-sm mt-1">{{ $row['assignment']->instructions ?: 'No instructions provided.' }}</div>
            </div>
        @empty
            <p class="text-slate-600">No assignments yet.</p>
        @endforelse
    </div>

    <div x-show="tab === 'assessments'" class="space-y-4">
        <div class="gc-panel p-5">
            <h3 class="text-xl font-semibold text-slate-800 mb-3">Assessments</h3>
            <p class="text-sm text-slate-600">Use each topic quiz link in Lessons to take assessments.</p>
        </div>
        <div class="gc-panel p-5">
            <h3 class="text-xl font-semibold text-slate-800 mb-3">Top Instructor Trainees</h3>
            @forelse($leaderboard as $index => $entry)
                <div class="flex items-center justify-between border-b py-2 text-sm">
                    <span>{{ $index + 1 }}. {{ $entry->user->name ?? 'Instructor' }}</span>
                    <span class="font-semibold">Score: {{ number_format((float) $entry->total_score, 2) }}%</span>
                </div>
            @empty
                <p class="text-slate-600">Leaderboard will appear as scores are recorded.</p>
            @endforelse
        </div>
    </div>

    <div x-show="tab === 'discussion'" class="gc-panel p-5 space-y-3">
        <h3 class="text-xl font-semibold text-slate-800">Course Discussion Forum</h3>
        <form method="POST" action="{{ route('instructor.training.discussions.store', $enrollment) }}" class="flex gap-2">
            @csrf
            <input class="border px-3 py-2 w-full" name="message" placeholder="Start a discussion..." required>
            <button class="gc-btn-primary" type="submit">Post</button>
        </form>
        @forelse($courseDiscussions as $discussion)
            <div class="border rounded p-3">
                <div class="text-sm font-semibold">{{ $discussion->user->name ?? 'User' }} <span class="text-slate-400 font-normal">· {{ $discussion->created_at?->diffForHumans() }}</span></div>
                <div class="text-sm mt-1">{{ $discussion->message }}</div>
                @if($discussion->replies->isNotEmpty())
                    <div class="mt-2 pl-3 border-l space-y-1 text-sm">
                        @foreach($discussion->replies as $reply)
                            <div><strong>{{ $reply->user->name ?? 'User' }}:</strong> {{ $reply->message }}</div>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('instructor.training.discussions.store', $enrollment) }}" class="mt-2 flex gap-2">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $discussion->id }}">
                    <input class="border px-3 py-2 w-full text-xs" name="message" placeholder="Reply..." required>
                    <button class="gc-btn-secondary text-xs px-3 py-1.5" type="submit">Reply</button>
                </form>
            </div>
        @empty
            <p class="text-slate-600">No discussions yet.</p>
        @endforelse
    </div>

    <div x-show="tab === 'live-classes'" class="gc-panel p-5 space-y-3">
        <h3 class="text-xl font-semibold text-slate-800">Upcoming Live Classes</h3>
        @forelse($upcomingLiveClasses as $liveClass)
            <div class="border rounded p-3">
                <div class="font-semibold">{{ $liveClass->title }}</div>
                @if($liveClass->description)
                    <div class="text-sm text-slate-600 mt-1">{{ $liveClass->description }}</div>
                @endif
                <div class="text-sm mt-2">Date: {{ $liveClass->start_time?->format('F j, Y') }}</div>
                <div class="text-sm">Time: {{ $liveClass->start_time?->format('g:i A') }} - {{ $liveClass->end_time?->format('g:i A') }}</div>
                <a href="{{ $liveClass->meeting_link }}" target="_blank" rel="noopener" class="gc-btn-primary inline-flex mt-2">Join</a>
            </div>
        @empty
            <p class="text-slate-600">No upcoming live classes.</p>
        @endforelse
    </div>

    <div x-show="tab === 'resources'" class="space-y-4">
        <div class="gc-panel p-5">
            <h3 class="text-xl font-semibold text-slate-800">Teaching Practice Submission</h3>
            <form method="POST" action="{{ route('instructor.training.teaching-practice.store', $enrollment) }}" class="mt-3 grid md:grid-cols-2 gap-2">
                @csrf
                <input class="border px-3 py-2" type="text" name="lesson_topic" placeholder="Lesson topic taught" required>
                <input class="border px-3 py-2" type="url" name="video_url" placeholder="Video URL" required>
                <textarea class="border px-3 py-2 md:col-span-2" name="description" rows="3" placeholder="Describe your teaching approach"></textarea>
                <button class="gc-btn-primary md:col-span-2 justify-center" type="submit">Submit Teaching Practice</button>
            </form>
            @if($teachingPractices->isNotEmpty())
                <div class="mt-4 space-y-2">
                    @foreach($teachingPractices as $practice)
                        <div class="border rounded p-3 text-sm">
                            <div class="font-semibold">{{ $practice->lesson_topic }}</div>
                            <a class="text-brand-700 underline text-xs" href="{{ $practice->video_url }}" target="_blank" rel="noopener">Open video</a>
                            @if($practice->score !== null)
                                <div class="mt-1">Score: <strong>{{ number_format((float) $practice->score, 2) }}%</strong></div>
                            @endif
                            @if($practice->instructor_feedback)
                                <div class="mt-1 text-slate-700">Feedback: {{ $practice->instructor_feedback }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="gc-panel p-5">
            <h3 class="text-xl font-semibold text-slate-800">Notifications</h3>
            @forelse($notifications as $notification)
                @php $data = $notification->data ?? []; @endphp
                <div class="border-b py-2 text-sm">
                    <div class="font-semibold">{{ $data['title'] ?? 'Notification' }}</div>
                    <div class="text-slate-600">{{ $data['message'] ?? '' }}</div>
                </div>
            @empty
                <p class="text-slate-600">No notifications yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
