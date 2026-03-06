<x-app-layout>
    <div class="space-y-6 max-w-6xl mx-auto">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-3xl gc-heading">{{ $cohort->name }}</h1>
                <p class="text-sm text-slate-600">
                    {{ $cohort->course->title ?? 'Course' }} ·
                    {{ $cohort->start_date?->format('Y-m-d') ?? '-' }} to {{ $cohort->end_date?->format('Y-m-d') ?? '-' }}
                </p>
            </div>
            <a href="{{ route('admin.training.index') }}" class="gc-btn-secondary">
                Back to Training
            </a>
        </div>

        @if(session('success'))
            <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Enroll Instructor</h2>
                <form method="POST" action="{{ route('admin.training.enroll', $cohort) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Instructor</label>
                        <select name="user_id" class="w-full" required>
                            <option value="">Select instructor</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}">{{ $instructor->name }} ({{ $instructor->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="gc-btn-primary">Enroll</button>
                </form>
            </div>

            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Cohort Info</h2>
                <ul class="text-sm text-slate-700 space-y-1">
                    <li>Status: {{ ucfirst($cohort->status) }}</li>
                    <li>Start: {{ $cohort->start_date?->format('Y-m-d') ?? '-' }}</li>
                    <li>End: {{ $cohort->end_date?->format('Y-m-d') ?? '-' }}</li>
                    <li>Duration: {{ $cohort->course?->duration_label ?? 'N/A' }}</li>
                </ul>
            </div>
        </div>

        <div class="gc-panel p-4">
            <h2 class="text-lg font-semibold mb-3">Enrollments</h2>
            @if($cohort->enrollments->isEmpty())
                <p class="text-slate-600">No enrollments yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="gc-table min-w-full">
                        <thead>
                            <tr>
                                <th>Instructor</th>
                                <th>Status</th>
                                <th>Certificate</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cohort->enrollments as $enrollment)
                                <tr>
                                    <td>
                                        {{ $enrollment->user->name ?? 'N/A' }}<br>
                                        <span class="text-xs text-slate-500">{{ $enrollment->user->email ?? '' }}</span>
                                    </td>
                                    <td>{{ ucfirst($enrollment->status) }}</td>
                                    <td>
                                        @if($enrollment->certification)
                                            <span class="text-emerald-700">Issued</span><br>
                                            <span class="text-xs text-slate-500">{{ $enrollment->certification->certificate_code }}</span><br>
                                            <a class="text-brand-700 underline text-xs"
                                               href="{{ route('admin.training.certificates.show', $enrollment->certification) }}">
                                                View Certificate
                                            </a>
                                        @else
                                            <span class="text-slate-500">Not issued</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.training.enrollments.update', $enrollment) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status">
                                                <option value="enrolled" @if($enrollment->status === 'enrolled') selected @endif>Enrolled</option>
                                                <option value="completed" @if($enrollment->status === 'completed') selected @endif>Completed</option>
                                                <option value="dropped" @if($enrollment->status === 'dropped') selected @endif>Dropped</option>
                                            </select>
                                            <button type="submit" class="ml-2 gc-btn-secondary text-xs px-3 py-1.5">Update</button>
                                        </form>

                                        @if($enrollment->isEligibleForCertification() && !$enrollment->certification)
                                            <form method="POST" action="{{ route('admin.training.enrollments.certificate', $enrollment) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="ml-2 gc-btn-primary text-xs px-3 py-1.5">Issue Certificate</button>
                                            </form>
                                        @elseif(!$enrollment->certification)
                                            <span class="ml-2 text-xs text-slate-500">Awaiting full completion requirements</span>
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

