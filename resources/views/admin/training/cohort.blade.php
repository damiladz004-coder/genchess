<x-app-layout>
    <div class="py-6 max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold">{{ $cohort->name }}</h1>
                <p class="text-sm text-gray-600">
                    {{ $cohort->course->title ?? 'Course' }} ·
                    {{ $cohort->start_date?->format('Y-m-d') ?? '-' }} to {{ $cohort->end_date?->format('Y-m-d') ?? '-' }}
                </p>
            </div>
            <a href="{{ route('admin.training.index') }}" class="text-blue-600 underline">
                Back to Training
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-700 bg-green-50 border border-green-200 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border rounded p-4">
                <h2 class="text-lg font-semibold mb-3">Enroll Instructor</h2>
                <form method="POST" action="{{ route('admin.training.enroll', $cohort) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Instructor</label>
                        <select name="user_id" class="border w-full px-3 py-2" required>
                            <option value="">Select instructor</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}">{{ $instructor->name }} ({{ $instructor->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Enroll
                    </button>
                </form>
            </div>

            <div class="bg-white border rounded p-4">
                <h2 class="text-lg font-semibold mb-3">Cohort Info</h2>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>Status: {{ ucfirst($cohort->status) }}</li>
                    <li>Start: {{ $cohort->start_date?->format('Y-m-d') ?? '-' }}</li>
                    <li>End: {{ $cohort->end_date?->format('Y-m-d') ?? '-' }}</li>
                    <li>Duration: {{ $cohort->course->duration_weeks ?? '-' }} weeks</li>
                </ul>
            </div>
        </div>

        <div class="mt-8 bg-white border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Enrollments</h2>
            @if($cohort->enrollments->isEmpty())
                <p class="text-gray-600">No enrollments yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-2 border-b">Instructor</th>
                                <th class="text-left px-4 py-2 border-b">Status</th>
                                <th class="text-left px-4 py-2 border-b">Certificate</th>
                                <th class="text-left px-4 py-2 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cohort->enrollments as $enrollment)
                                <tr class="border-b">
                                    <td class="px-4 py-2">
                                        {{ $enrollment->user->name ?? 'N/A' }}<br>
                                        <span class="text-xs text-gray-500">{{ $enrollment->user->email ?? '' }}</span>
                                    </td>
                                    <td class="px-4 py-2">{{ ucfirst($enrollment->status) }}</td>
                                    <td class="px-4 py-2">
                                        @if($enrollment->certification)
                                            <span class="text-green-700">Issued</span><br>
                                            <span class="text-xs text-gray-500">{{ $enrollment->certification->certificate_code }}</span><br>
                                            <a class="text-blue-600 underline text-xs"
                                               href="{{ route('admin.training.certificates.show', $enrollment->certification) }}">
                                                View Certificate
                                            </a>
                                        @else
                                            <span class="text-gray-500">Not issued</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <form method="POST" action="{{ route('admin.training.enrollments.update', $enrollment) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="border px-2 py-1">
                                                <option value="enrolled" @if($enrollment->status === 'enrolled') selected @endif>Enrolled</option>
                                                <option value="completed" @if($enrollment->status === 'completed') selected @endif>Completed</option>
                                                <option value="dropped" @if($enrollment->status === 'dropped') selected @endif>Dropped</option>
                                            </select>
                                            <button type="submit" class="ml-2 bg-gray-800 text-white px-3 py-1 rounded">
                                                Update
                                            </button>
                                        </form>

                                        @if($enrollment->status === 'completed' && !$enrollment->certification)
                                            <form method="POST" action="{{ route('admin.training.enrollments.certificate', $enrollment) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="ml-2 bg-green-700 text-white px-3 py-1 rounded">
                                                    Issue Certificate
                                                </button>
                                            </form>
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
