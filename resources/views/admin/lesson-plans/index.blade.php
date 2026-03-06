<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-3xl gc-heading">Instructor Lesson Plan Reviews</h1>
            <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="GET" class="gc-panel p-4 grid md:grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Review Status</label>
                <select name="review_status" class="w-full">
                    <option value="">All</option>
                    @foreach(['draft', 'submitted', 'changes_requested', 'approved'] as $status)
                        <option value="{{ $status }}" @selected(request('review_status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Instructor</label>
                <select name="instructor_id" class="w-full">
                    <option value="">All</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" @selected((string) request('instructor_id') === (string) $instructor->id)>
                            {{ $instructor->name }} ({{ $instructor->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button class="gc-btn-primary" type="submit">Filter</button>
                <a href="{{ route('admin.lesson-plans.index') }}" class="gc-btn-secondary">Reset</a>
            </div>
        </form>

        <div class="gc-panel p-4">
            @if($plans->isEmpty())
                <p class="text-slate-600">No lesson plans found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="gc-table min-w-full">
                        <thead>
                            <tr>
                                <th>Instructor</th>
                                <th>Class</th>
                                <th>Topic</th>
                                <th>Review Status</th>
                                <th>Submitted</th>
                                <th>Feedback</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                                <tr class="align-top">
                                    <td>
                                        {{ $plan->instructor->name ?? 'N/A' }}<br>
                                        <span class="text-xs text-slate-500">{{ $plan->instructor->email ?? '' }}</span>
                                    </td>
                                    <td>{{ $plan->classroom->name ?? 'N/A' }}</td>
                                    <td class="font-medium">{{ $plan->topic }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $plan->review_status ?? 'draft')) }}</td>
                                    <td>{{ $plan->submitted_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td class="max-w-sm">
                                        {{ $plan->review_feedback ?: '-' }}
                                        @if($plan->reviewer)
                                            <div class="text-xs text-slate-500 mt-1">
                                                By {{ $plan->reviewer->name }} {{ $plan->reviewed_at ? 'on ' . $plan->reviewed_at->format('Y-m-d H:i') : '' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="min-w-[280px]">
                                        @if($plan->review_status === 'submitted')
                                            <form method="POST" action="{{ route('admin.lesson-plans.review', $plan) }}" class="space-y-2">
                                                @csrf
                                                @method('PATCH')
                                                <textarea name="review_feedback" rows="2" class="w-full" placeholder="Feedback for instructor (required if requesting changes)"></textarea>
                                                <div class="flex gap-2">
                                                    <button type="submit" name="decision" value="approved" class="gc-btn-primary text-xs px-3 py-1.5">
                                                        Approve
                                                    </button>
                                                    <button type="submit" name="decision" value="changes_requested" class="gc-btn-secondary text-xs px-3 py-1.5">
                                                        Request Changes
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-500">Awaiting instructor submission</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
