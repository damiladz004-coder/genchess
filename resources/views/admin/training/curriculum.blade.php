<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ $course->title }} - Curriculum</h1>
                <p class="text-sm text-gray-600">Manage modules, topics, quiz rules, and assignments.</p>
            </div>
            <a href="{{ route('admin.training.index') }}" class="text-blue-600 underline">Back to Training</a>
        </div>

        @if(session('success'))
            <div class="rounded border border-green-200 bg-green-50 px-4 py-2 text-green-700">{{ session('success') }}</div>
        @endif

        <div class="bg-white border rounded p-4">
            <h2 class="font-semibold mb-3">Add Module</h2>
            <form method="POST" action="{{ route('admin.training.modules.store', $course) }}" class="grid md:grid-cols-5 gap-3">
                @csrf
                <input class="border px-3 py-2" type="number" name="module_number" min="1" placeholder="Module #">
                <input class="border px-3 py-2 md:col-span-2" type="text" name="title" placeholder="Module title">
                <input class="border px-3 py-2" type="number" name="sort_order" min="0" placeholder="Sort">
                <button class="bg-blue-600 text-white px-4 py-2 rounded" type="submit">Save Module</button>
                <input class="border px-3 py-2 md:col-span-5" type="text" name="goal" placeholder="Goal (optional)">
            </form>
        </div>

        @foreach($course->modules as $module)
            <div class="bg-white border rounded p-4 space-y-4">
                <div class="flex items-center justify-between">
                    <form method="POST" action="{{ route('admin.training.modules.update', $module) }}" class="grid md:grid-cols-6 gap-2 w-full">
                        @csrf
                        @method('PATCH')
                        <input class="border px-2 py-1" type="number" name="module_number" value="{{ $module->module_number }}" min="1">
                        <input class="border px-2 py-1 md:col-span-2" type="text" name="title" value="{{ $module->title }}">
                        <input class="border px-2 py-1" type="number" name="sort_order" value="{{ $module->sort_order }}" min="0">
                        <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="is_capstone" value="1" @checked($module->is_capstone)> Capstone</label>
                        <button class="bg-gray-800 text-white px-3 py-1 rounded" type="submit">Update</button>
                        <input class="border px-2 py-1 md:col-span-5" type="text" name="goal" value="{{ $module->goal }}" placeholder="Goal">
                    </form>
                    <form method="POST" action="{{ route('admin.training.modules.destroy', $module) }}" onsubmit="return confirm('Delete module and all its topics?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 text-sm underline ml-3" type="submit">Delete</button>
                    </form>
                </div>

                <div class="border-t pt-4">
                    <h3 class="font-semibold mb-2">Add Topic</h3>
                    <form method="POST" action="{{ route('admin.training.topics.store', $module) }}" class="grid md:grid-cols-6 gap-2">
                        @csrf
                        <input class="border px-2 py-1" type="number" name="topic_number" min="1" placeholder="Topic #">
                        <input class="border px-2 py-1 md:col-span-2" type="text" name="title" placeholder="Topic title">
                        <input class="border px-2 py-1" type="number" name="duration_minutes" min="1" placeholder="Minutes">
                        <select class="border px-2 py-1" name="level">
                            <option value="beginner">Beginner</option>
                            <option value="advanced">Advanced</option>
                        </select>
                        <button class="bg-green-700 text-white px-3 py-1 rounded" type="submit">Add Topic</button>
                    </form>
                </div>

                @foreach($module->topics as $topic)
                    <div class="border rounded p-3 bg-gray-50 space-y-3">
                        <form method="POST" action="{{ route('admin.training.topics.update', $topic) }}" class="grid md:grid-cols-7 gap-2">
                            @csrf
                            @method('PATCH')
                            <input class="border px-2 py-1" type="number" name="topic_number" value="{{ $topic->topic_number }}" min="1">
                            <input class="border px-2 py-1 md:col-span-2" type="text" name="title" value="{{ $topic->title }}">
                            <input class="border px-2 py-1" type="number" name="duration_minutes" value="{{ $topic->duration_minutes }}" min="1">
                            <select class="border px-2 py-1" name="level">
                                <option value="beginner" @selected($topic->level === 'beginner')>Beginner</option>
                                <option value="advanced" @selected($topic->level === 'advanced')>Advanced</option>
                            </select>
                            <input class="border px-2 py-1" type="number" name="sort_order" value="{{ $topic->sort_order }}" min="0">
                            <button class="bg-gray-800 text-white px-3 py-1 rounded" type="submit">Update Topic</button>
                        </form>
                        <form method="POST" action="{{ route('admin.training.topics.destroy', $topic) }}" onsubmit="return confirm('Delete topic?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 text-sm underline" type="submit">Delete Topic</button>
                        </form>

                        <div class="grid md:grid-cols-2 gap-3">
                            <form method="POST" action="{{ route('admin.training.quizzes.upsert', $topic) }}" class="border rounded p-3 bg-white space-y-2">
                                @csrf
                                <h4 class="font-semibold text-sm">Quiz Settings</h4>
                                <div class="grid grid-cols-4 gap-2">
                                    <input class="border px-2 py-1" type="number" name="mcq_count" min="5" max="10" value="{{ $topic->quiz->mcq_count ?? 5 }}">
                                    <input class="border px-2 py-1" type="number" name="true_false_count" min="2" max="2" value="{{ $topic->quiz->true_false_count ?? 2 }}">
                                    <input class="border px-2 py-1" type="number" name="scenario_count" min="1" max="1" value="{{ $topic->quiz->scenario_count ?? 1 }}">
                                    <input class="border px-2 py-1" type="number" step="0.01" name="pass_mark" min="1" max="100" value="{{ $topic->quiz->pass_mark ?? 70 }}">
                                </div>
                                <button class="bg-indigo-700 text-white px-3 py-1 rounded text-sm" type="submit">Save Quiz</button>
                            </form>

                            <form method="POST" action="{{ route('admin.training.assignments.store', $topic) }}" class="border rounded p-3 bg-white space-y-2">
                                @csrf
                                <h4 class="font-semibold text-sm">Add Assignment</h4>
                                <input class="border px-2 py-1 w-full" type="text" name="title" placeholder="Assignment title">
                                <input class="border px-2 py-1 w-full" type="text" name="type" placeholder="Type (e.g. puzzle_solving)">
                                <input class="border px-2 py-1 w-full" type="text" name="instructions" placeholder="Instructions">
                                <button class="bg-emerald-700 text-white px-3 py-1 rounded text-sm" type="submit">Add Assignment</button>
                            </form>
                        </div>

                        @if($topic->quiz)
                            <div class="border rounded p-3 bg-white space-y-3">
                                <h4 class="font-semibold text-sm">Quiz Questions</h4>
                                <form method="POST" action="{{ route('admin.training.quiz-questions.store', $topic->quiz) }}" class="grid md:grid-cols-6 gap-2">
                                    @csrf
                                    <select class="border px-2 py-1" name="type" required>
                                        <option value="mcq">MCQ</option>
                                        <option value="true_false">True / False</option>
                                        <option value="scenario">Scenario</option>
                                    </select>
                                    <input class="border px-2 py-1 md:col-span-2" type="text" name="question" placeholder="Question" required>
                                    <input class="border px-2 py-1" type="text" name="correct_answer" placeholder="Correct answer" required>
                                    <input class="border px-2 py-1" type="number" name="sort_order" min="0" placeholder="Sort">
                                    <button class="bg-indigo-700 text-white px-3 py-1 rounded text-sm" type="submit">Add Question</button>
                                    <textarea class="border px-2 py-1 md:col-span-6" rows="2" name="options_text" placeholder="Options (one per line). Not needed for Scenario."></textarea>
                                    <input class="border px-2 py-1 md:col-span-6" type="text" name="explanation" placeholder="Explanation (optional)">
                                </form>

                                @forelse($topic->quiz->questions as $question)
                                    @php $optionsText = is_array($question->options) ? implode(PHP_EOL, $question->options) : ''; @endphp
                                    <div class="border rounded p-2 bg-slate-50">
                                        <form method="POST" action="{{ route('admin.training.quiz-questions.update', $question) }}" class="grid md:grid-cols-6 gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select class="border px-2 py-1" name="type" required>
                                                <option value="mcq" @selected($question->type === 'mcq')>MCQ</option>
                                                <option value="true_false" @selected($question->type === 'true_false')>True / False</option>
                                                <option value="scenario" @selected($question->type === 'scenario')>Scenario</option>
                                            </select>
                                            <input class="border px-2 py-1 md:col-span-2" type="text" name="question" value="{{ $question->question }}" required>
                                            <input class="border px-2 py-1" type="text" name="correct_answer" value="{{ $question->correct_answer }}" required>
                                            <input class="border px-2 py-1" type="number" name="sort_order" min="0" value="{{ $question->sort_order }}">
                                            <button class="bg-gray-700 text-white px-3 py-1 rounded text-sm" type="submit">Update</button>
                                            <textarea class="border px-2 py-1 md:col-span-6" rows="2" name="options_text">{{ $optionsText }}</textarea>
                                            <input class="border px-2 py-1 md:col-span-6" type="text" name="explanation" value="{{ $question->explanation }}" placeholder="Explanation">
                                        </form>
                                        <form method="POST" action="{{ route('admin.training.quiz-questions.destroy', $question) }}" class="mt-1" onsubmit="return confirm('Delete question?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 text-xs underline" type="submit">Delete</button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-500">No questions yet.</p>
                                @endforelse
                            </div>
                        @endif

                        @if($topic->assignments->isNotEmpty())
                            <div class="space-y-2">
                                @foreach($topic->assignments as $assignment)
                                    <div class="bg-white border rounded p-2">
                                        <form method="POST" action="{{ route('admin.training.assignments.update', $assignment) }}" class="grid md:grid-cols-6 gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input class="border px-2 py-1 md:col-span-2" type="text" name="title" value="{{ $assignment->title }}">
                                            <input class="border px-2 py-1" type="text" name="type" value="{{ $assignment->type }}">
                                            <input class="border px-2 py-1 md:col-span-2" type="text" name="instructions" value="{{ $assignment->instructions }}">
                                            <button class="bg-gray-700 text-white px-3 py-1 rounded text-sm" type="submit">Update</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.training.assignments.destroy', $assignment) }}" class="mt-1" onsubmit="return confirm('Delete assignment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 text-xs underline" type="submit">Delete</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="bg-white border rounded p-4 space-y-3">
            <h2 class="font-semibold">Pending Assignment Reviews</h2>
            @forelse($pendingSubmissions as $submission)
                <div class="border rounded p-3">
                    <div class="text-sm mb-2">
                        <strong>{{ $submission->enrollment->user->name ?? 'Instructor' }}</strong> -
                        {{ $submission->topic->title }} -
                        {{ $submission->assignment->title }}
                    </div>
                    <div class="text-xs text-gray-600 mb-2">
                        {{ $submission->submission_text ?: '-' }} @if($submission->submission_url) | <a class="text-blue-600 underline" target="_blank" href="{{ $submission->submission_url }}">Open URL</a> @endif
                    </div>
                    <form method="POST" action="{{ route('admin.training.submissions.review', $submission) }}" class="grid md:grid-cols-4 gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="border px-2 py-1">
                            <option value="approved">Approve</option>
                            <option value="needs_revision">Needs Revision</option>
                            <option value="rejected">Reject</option>
                        </select>
                        <input class="border px-2 py-1 md:col-span-2" type="text" name="mentor_feedback" placeholder="Feedback">
                        <button class="bg-blue-700 text-white px-3 py-1 rounded" type="submit">Submit Review</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-600">No pending submissions.</p>
            @endforelse
        </div>

        <div class="bg-white border rounded p-4 space-y-3">
            <h2 class="font-semibold">Capstone Reviews</h2>
            @forelse($pendingCapstone as $capstone)
                <div class="border rounded p-3">
                    <div class="text-sm mb-2">
                        <strong>{{ $capstone->enrollment->user->name ?? 'Instructor' }}</strong> -
                        Status: {{ ucfirst(str_replace('_', ' ', $capstone->status)) }}
                    </div>
                    @if($capstone->video_url)
                        <a class="text-blue-600 underline text-sm" target="_blank" href="{{ $capstone->video_url }}">Open video</a>
                    @endif
                    <form method="POST" action="{{ route('admin.training.capstone.review', $capstone) }}" class="grid md:grid-cols-4 gap-2 mt-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="border px-2 py-1">
                            <option value="reviewed">Reviewed</option>
                            <option value="resubmission_required">Resubmission Required</option>
                            <option value="approved">Approved</option>
                        </select>
                        <input class="border px-2 py-1 md:col-span-2" type="text" name="mentor_feedback" placeholder="Feedback">
                        <button class="bg-blue-700 text-white px-3 py-1 rounded" type="submit">Update</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-600">No capstone reviews pending.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
