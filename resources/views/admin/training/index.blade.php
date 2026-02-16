<x-app-layout>
    <div class="py-6 max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Instructor Training Portal</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 underline">Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-700 bg-green-50 border border-green-200 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border rounded p-4">
                <h2 class="text-lg font-semibold mb-3">Create Course</h2>
                <form method="POST" action="{{ route('admin.training.courses.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Title</label>
                        <input name="title" class="border w-full px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" class="border w-full px-3 py-2" rows="3"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Duration (weeks)</label>
                        <input type="number" name="duration_weeks" class="border w-full px-3 py-2" value="4" min="1" max="52" required>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Save Course
                    </button>
                </form>
            </div>

            <div class="bg-white border rounded p-4">
                <h2 class="text-lg font-semibold mb-3">Create Cohort</h2>
                <form method="POST" action="{{ route('admin.training.cohorts.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Course</label>
                        <select name="course_id" class="border w-full px-3 py-2" required>
                            <option value="">Select course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Cohort Name</label>
                        <input name="name" class="border w-full px-3 py-2" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Start Date</label>
                            <input type="date" name="start_date" class="border w-full px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">End Date</label>
                            <input type="date" name="end_date" class="border w-full px-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status" class="border w-full px-3 py-2" required>
                            <option value="planned">Planned</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded">
                        Save Cohort
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-8 bg-white border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Cohorts</h2>
            @if($cohorts->isEmpty())
                <p class="text-gray-600">No cohorts yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-2 border-b">Course</th>
                                <th class="text-left px-4 py-2 border-b">Cohort</th>
                                <th class="text-left px-4 py-2 border-b">Dates</th>
                                <th class="text-left px-4 py-2 border-b">Status</th>
                                <th class="text-left px-4 py-2 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cohorts as $cohort)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $cohort->course->title ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $cohort->name }}</td>
                                    <td class="px-4 py-2">
                                        {{ $cohort->start_date?->format('Y-m-d') ?? '-' }}
                                        to
                                        {{ $cohort->end_date?->format('Y-m-d') ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2">{{ ucfirst($cohort->status) }}</td>
                                    <td class="px-4 py-2">
                                        <a class="text-blue-600 underline" href="{{ route('admin.training.cohorts.show', $cohort) }}">
                                            Manage
                                        </a>
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
