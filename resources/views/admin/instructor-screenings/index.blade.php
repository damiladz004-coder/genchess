@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="gc-heading text-3xl">Instructor Screening Workflow</h2>
            <p class="text-sm text-slate-600">Track stage one results, interview scheduling, approvals, and onboarding delivery.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.instructor-screenings.export', request()->query()) }}" class="gc-btn-secondary">Export CSV</a>
            <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back</a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Total Attempts</div><div class="text-2xl font-bold text-brand-800">{{ $totals['all'] }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Passed</div><div class="text-2xl font-bold text-emerald-700">{{ $totals['passed'] }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Failed</div><div class="text-2xl font-bold text-rose-700">{{ $totals['failed'] }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Approved</div><div class="text-2xl font-bold text-sky-700">{{ $totals['approved'] }}</div></div>
    </div>

    <form method="GET" class="gc-panel p-4">
        <div class="grid gap-4 md:grid-cols-[1.5fr_1fr_auto]">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-600">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Name, email, phone, location">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-600">Status</label>
                <select name="status">
                    <option value="">All</option>
                    <option value="passed" @selected(request('status') === 'passed')>Passed</option>
                    <option value="failed" @selected(request('status') === 'failed')>Failed</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="gc-btn-primary">Filter</button>
                <a href="{{ route('admin.instructor-screenings.index') }}" class="gc-btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    @if($screenings->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No screening attempts yet.</div>
    @else
        <div class="hidden overflow-x-auto gc-panel lg:block">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Candidate</th>
                        <th>Preferred Slot</th>
                        <th>Score</th>
                        <th>Stage 2</th>
                        <th>Stage 3</th>
                        <th>Final Status</th>
                        <th>Onboarding</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($screenings as $screening)
                        <tr>
                            <td>{{ optional($screening->submitted_at)->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="font-medium text-slate-800">{{ $screening->name }}</div>
                                <div class="text-xs text-slate-500">{{ $screening->email }} · {{ $screening->phone ?? 'No phone' }}</div>
                            </td>
                            <td>
                                {{ optional($screening->preferred_interview_date)->format('Y-m-d') ?? 'Not set' }}
                                <div class="text-xs text-slate-500">{{ $screening->preferred_interview_time?->format('H:i') ?? '-' }}</div>
                            </td>
                            <td>{{ $screening->score }}/{{ $screening->total_questions }} ({{ $screening->percentage }}%)</td>
                            <td>{{ ucfirst($screening->stage_two_status) }}<div class="text-xs text-slate-500">{{ optional($screening->stage_two_meeting_date)->format('Y-m-d') ?? 'Not scheduled' }}</div></td>
                            <td>{{ ucfirst($screening->stage_three_status) }}<div class="text-xs text-slate-500">{{ optional($screening->stage_three_meeting_date)->format('Y-m-d') ?? 'Not scheduled' }}</div></td>
                            <td>{{ strtoupper(str_replace('_', ' ', $screening->final_status)) }}</td>
                            <td>{{ $screening->onboarding_link_sent_at ? $screening->onboarding_link_sent_at->format('Y-m-d H:i') : 'Pending' }}</td>
                            <td><a href="{{ route('admin.instructor-screenings.show', $screening) }}" class="text-sm font-semibold text-brand-700">Manage</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid gap-4 lg:hidden">
            @foreach($screenings as $screening)
                <article class="gc-panel p-4 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-slate-900">{{ $screening->name }}</h3>
                            <p class="text-sm text-slate-500">{{ $screening->email }}</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ strtoupper(str_replace('_', ' ', $screening->final_status)) }}</span>
                    </div>
                    <div class="grid gap-2 text-sm text-slate-700 sm:grid-cols-2">
                        <div><span class="text-slate-500">Phone:</span> {{ $screening->phone ?? '-' }}</div>
                        <div><span class="text-slate-500">Location:</span> {{ $screening->location ?? '-' }}</div>
                        <div><span class="text-slate-500">Preferred:</span> {{ optional($screening->preferred_interview_date)->format('Y-m-d') ?? '-' }} {{ $screening->preferred_interview_time?->format('H:i') ?? '' }}</div>
                        <div><span class="text-slate-500">Score:</span> {{ $screening->percentage }}%</div>
                        <div><span class="text-slate-500">Stage 2:</span> {{ ucfirst($screening->stage_two_status) }}</div>
                        <div><span class="text-slate-500">Stage 3:</span> {{ ucfirst($screening->stage_three_status) }}</div>
                    </div>
                    <a href="{{ route('admin.instructor-screenings.show', $screening) }}" class="gc-btn-secondary inline-flex">Manage Workflow</a>
                </article>
            @endforeach
        </div>

        {{ $screenings->links() }}
    @endif
</div>
@endsection
