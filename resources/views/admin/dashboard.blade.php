<x-app-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl gc-heading">Genchess Super Admin Dashboard</h1>
            <p class="mt-2 text-slate-600">
            Welcome, {{ auth()->user()->name ?? 'User' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-10 gap-4">
            <div class="gc-panel p-4">
                <h3 class="text-sm text-slate-500">Total Schools</h3>
                <p class="text-2xl font-bold text-brand-800">{{ $totalSchools }}</p>
            </div>

            <div class="gc-panel p-4">
                <h3 class="text-sm text-slate-500">Pending Schools</h3>
                <p class="text-2xl font-bold text-amber-700">{{ $pendingSchools }}</p>
            </div>

            <div class="gc-panel p-4">
                <h3 class="text-sm text-slate-500">Active Schools</h3>
                <p class="text-2xl font-bold text-emerald-700">{{ $activeSchools }}</p>
            </div>

            <div class="gc-panel p-4">
                <h3 class="text-sm text-slate-500">Total Students</h3>
                <p class="text-2xl font-bold text-brand-800">{{ $totalStudents }}</p>
            </div>

            <div class="gc-panel p-4">
                <h3 class="text-sm text-slate-500">Total Instructors</h3>
                <p class="text-2xl font-bold text-indigo-700">{{ $totalInstructors }}</p>
            </div>

            <div class="gc-panel p-4">
                <h3 class="text-sm text-slate-500">Outstanding Payments</h3>
                <p class="text-2xl font-bold text-rose-700">{{ $totalOutstanding }}</p>
            </div>

            <div class="gc-panel p-4">
                <h3 class="text-sm text-slate-500">Total Paid Payments</h3>
                <p class="text-2xl font-bold text-sky-700">{{ $totalPaidPayments }}</p>
            </div>

            <div class="gc-panel p-4">
                <h3 class="text-sm text-slate-500">Training Payments</h3>
                <p class="text-2xl font-bold text-brand-800">{{ $trainingPayments }}</p>
            </div>

            <div class="gc-panel p-4">
                <h3 class="text-sm text-slate-500">Store Payments</h3>
                <p class="text-2xl font-bold text-emerald-700">{{ $storePayments }}</p>
            </div>

            <div class="gc-panel p-4">
                <h3 class="text-sm text-slate-500">School Payments</h3>
                <p class="text-2xl font-bold text-indigo-700">{{ $schoolPayments }}</p>
            </div>
        </div>

        <div class="gc-panel p-4 flex flex-wrap gap-2">
            <a href="{{ route('admin.schools.index') }}"
               class="gc-btn-primary">
                Review Pending Schools
            </a>
            <a href="{{ route('admin.enrollments.index') }}"
               class="gc-btn-secondary">
                View Enrollment Requests
            </a>
            <a href="{{ route('admin.finance.index') }}"
               class="gc-btn-secondary">
                Finance Tracking
            </a>
            <a href="{{ route('admin.training.index') }}"
               class="gc-btn-secondary">
                Instructor Training
            </a>
            <a href="{{ route('admin.careers.index') }}"
               class="gc-btn-secondary">
                Careers
            </a>
            <a href="{{ route('admin.class-teacher-feedback.index') }}"
               class="gc-btn-secondary">
                Class Teacher Feedback
            </a>
            <a href="{{ route('admin.instructor-assignments.index') }}"
               class="gc-btn-secondary">
                Instructor Assignments
            </a>
            <a href="{{ route('admin.timetables.index') }}"
               class="gc-btn-secondary">
                Timetables
            </a>
            <a href="{{ route('admin.classes.index') }}"
               class="gc-btn-secondary">
                Classes (Review)
            </a>
            <a href="{{ route('admin.students.index') }}"
               class="gc-btn-secondary">
                Students (Approval)
            </a>
            <a href="{{ route('admin.exams.templates.index') }}"
               class="gc-btn-secondary">
                Exam Templates
            </a>
            <a href="{{ route('admin.exams.attempts.index') }}"
               class="gc-btn-secondary">
                Exam Attempts
            </a>
            <a href="{{ route('admin.scheme.index') }}"
               class="gc-btn-secondary">
                Scheme of Work
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-2">Schools by Status</h2>
                @if(empty($statusCounts))
                    <p class="text-slate-600">No schools found.</p>
                @else
                    @php
                        $statusMax = max($statusCounts);
                        $barWidth = 320;
                        $barHeight = 16;
                        $gap = 10;
                        $chartHeight = (count($statusCounts) * ($barHeight + $gap));
                    @endphp
                    <svg width="{{ $barWidth + 140 }}" height="{{ $chartHeight }}">
                        @php $y = 0; @endphp
                        @foreach($statusCounts as $status => $count)
                            @php
                                $label = ucfirst($status ?? 'unknown');
                                $w = $statusMax > 0 ? intval(($count / $statusMax) * $barWidth) : 0;
                            @endphp
                            <text x="0" y="{{ $y + 12 }}" font-size="12" fill="#374151">{{ $label }}</text>
                            <rect x="120" y="{{ $y }}" width="{{ $w }}" height="{{ $barHeight }}" fill="#2563eb"></rect>
                            <text x="{{ 120 + $w + 8 }}" y="{{ $y + 12 }}" font-size="12" fill="#374151">{{ $count }}</text>
                            @php $y += ($barHeight + $gap); @endphp
                        @endforeach
                    </svg>
                @endif
            </div>

            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-2">Enrollment Requests (Last 6 Months)</h2>
                @if(empty($requestsByMonth))
                    <p class="text-slate-600">No enrollment requests yet.</p>
                @else
                    @php
                        $monthMax = max($requestsByMonth);
                        $barWidth = 320;
                        $barHeight = 16;
                        $gap = 10;
                        $chartHeight = (count($requestsByMonth) * ($barHeight + $gap));
                    @endphp
                    <svg width="{{ $barWidth + 140 }}" height="{{ $chartHeight }}">
                        @php $y = 0; @endphp
                        @foreach($requestsByMonth as $month => $count)
                            @php
                                $w = $monthMax > 0 ? intval(($count / $monthMax) * $barWidth) : 0;
                            @endphp
                            <text x="0" y="{{ $y + 12 }}" font-size="12" fill="#374151">{{ $month }}</text>
                            <rect x="120" y="{{ $y }}" width="{{ $w }}" height="{{ $barHeight }}" fill="#10b981"></rect>
                            <text x="{{ 120 + $w + 8 }}" y="{{ $y + 12 }}" font-size="12" fill="#374151">{{ $count }}</text>
                            @php $y += ($barHeight + $gap); @endphp
                        @endforeach
                    </svg>
                @endif
            </div>
        </div>

        <div class="gc-panel p-4">
            <h2 class="text-lg font-semibold mb-2">Schools by State</h2>
            @if($schoolsByState->isEmpty())
                <p class="text-slate-600">No schools found.</p>
            @else
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>State</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schoolsByState as $row)
                                <tr>
                                    <td>{{ $row->state ?? 'N/A' }}</td>
                                    <td>{{ $row->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="gc-panel p-4">
            <h2 class="text-lg font-semibold mb-2">Recent Approvals</h2>
            @if($recentApprovals->isEmpty())
                <p class="text-slate-600">No recent approvals.</p>
            @else
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>School</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Approved On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApprovals as $school)
                                <tr>
                                    <td>{{ $school->school_name }}</td>
                                    <td>{{ $school->city }}</td>
                                    <td>{{ $school->state }}</td>
                                    <td>
                                        {{ optional($school->updated_at)->format('Y-m-d') }}
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
