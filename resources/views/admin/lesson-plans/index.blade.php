<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Instructor Lesson Plan Reviews</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 underline">Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="text-green-700 bg-green-50 border border-green-200 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="text-rose-700 bg-rose-50 border border-rose-200 px-4 py-2 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="GET" class="bg-white border rounded p-4 grid md:grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-medium mb-1">Review Status</label>
                <select name="review_status" class="border w-full px-3 py-2">
                    <option value="">All</option>
                    @foreach(['draft', 'submitted', 'changes_requested', 'approved'] as $status)
                        <option value="{{ $status }}" @selected(request('review_status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Instructor</label>
                <select name="instructor_id" class="border w-full px-3 py-2">
                    <option value="">All</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" @selected((string) request('instructor_id') === (string) $instructor->id)>
                            {{ $instructor->name }} ({{ $instructor->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded" type="submit">Filter</button>
                <a href="{{ route('admin.lesson-plans.index') }}" class="px-4 py-2 rounded border">Reset</a>
            </div>
        </form>

        <div class="bg-white border rounded p-4">
            @if($plans->isEmpty())
                <p class="text-slate-600">No lesson plans found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-2 border-b">Instructor</th>
                                <th class="text-left px-4 py-2 border-b">Class</th>
                                <th class="text-left px-4 py-2 border-b">Topic</th>
                                <th class="text-left px-4 py-2 border-b">Review Status</th>
                                <th class="text-left px-4 py-2 border-b">Submitted</th>
                                <th class="text-left px-4 py-2 border-b">Feedback</th>
                                <th class="text-left px-4 py-2 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                                <tr class="border-b align-top">
                                    <td class="px-4 py-2">
                                        {{ $plan->instructor->name ?? 'N/A' }}<br>
                                        <span class="text-xs text-slate-500">{{ $plan->instructor->email ?? '' }}</span>
                                    </td>
                                    <td class="px-4 py-2">{{ $plan->classroom->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 font-medium">{{ $plan->topic }}</td>
                                    <td class="px-4 py-2">{{ ucfirst(str_replace('_', ' ', $plan->review_status ?? 'draft')) }}</td>
                                    <td class="px-4 py-2">{{ $plan->submitted_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td class="px-4 py-2 max-w-sm">
                                        {{ $plan->review_feedback ?: '-' }}
                                        @if($plan->reviewer)
                                            <div class="text-xs text-slate-500 mt-1">
                                                By {{ $plan->reviewer->name }} {{ $plan->reviewed_at ? 'on ' . $plan->reviewed_at->format('Y-m-d H:i') : '' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 min-w-[280px]">
                                        @if($plan->review_status === 'submitted')
                                            <form method="POST" action="{{ route('admin.lesson-plans.review', $plan) }}" class="space-y-2">
                                                @csrf
                                                @method('PATCH')
                                                <textarea name="review_feedback" rows="2" class="border w-full px-2 py-1" placeholder="Feedback for instructor (required if requesting changes)"></textarea>
                                                <div class="flex gap-2">
                                                    <button type="submit" name="decision" value="approved" class="bg-emerald-600 text-white px-3 py-1 rounded">
                                                        Approve
                                                    </button>
                                                    <button type="submit" name="decision" value="changes_requested" class="bg-amber-600 text-white px-3 py-1 rounded">
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
