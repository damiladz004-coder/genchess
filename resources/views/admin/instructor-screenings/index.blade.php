@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Instructor Screening Results</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.instructor-screenings.export', request()->query()) }}" class="gc-btn-secondary">Export CSV</a>
            <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="gc-panel p-4">
            <div class="text-xs text-slate-500 uppercase tracking-wide">Total Attempts</div>
            <div class="text-2xl font-bold text-brand-800">{{ $totals['all'] }}</div>
        </div>
        <div class="gc-panel p-4">
            <div class="text-xs text-slate-500 uppercase tracking-wide">Passed</div>
            <div class="text-2xl font-bold text-emerald-700">{{ $totals['passed'] }}</div>
        </div>
        <div class="gc-panel p-4">
            <div class="text-xs text-slate-500 uppercase tracking-wide">Failed</div>
            <div class="text-2xl font-bold text-rose-700">{{ $totals['failed'] }}</div>
        </div>
    </div>

    <form method="GET" class="gc-panel p-4">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Name, email, location">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                <select name="status">
                    <option value="">All</option>
                    <option value="passed" @selected(request('status') === 'passed')>Passed</option>
                    <option value="failed" @selected(request('status') === 'failed')>Failed</option>
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
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Interview Mode</th>
                        <th>Score</th>
                        <th>Result</th>
                        <th>Workflow</th>
                        <th>Invitation Status</th>
                        <th>Invitation Sent At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($screenings as $screening)
                        <tr>
                            <td>{{ optional($screening->submitted_at)->format('Y-m-d H:i') }}</td>
                            <td class="font-medium text-slate-800">{{ $screening->name }}</td>
                            <td>{{ $screening->email }}</td>
                            <td>{{ $screening->phone ?? '-' }}</td>
                            <td>{{ $screening->location ?? '-' }}</td>
                            <td>{{ strtoupper($screening->interview_mode) }}</td>
                            <td>{{ $screening->score }}/{{ $screening->total_questions }} ({{ $screening->percentage }}%)</td>
                            <td>
                                @if($screening->passed)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Passed</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Failed</span>
                                @endif
                            </td>
                            <td>
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ strtoupper(str_replace('_', ' ', $screening->final_status ?? 'pending')) }}
                                </span>
                            </td>
                            <td>
                                @if(!$screening->passed)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">N/A</span>
                                @elseif($screening->invitation_sent_at)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Sent</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Failed</span>
                                @endif
                            </td>
                            <td>{{ $screening->invitation_sent_at ? $screening->invitation_sent_at->format('Y-m-d H:i') : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.instructor-screenings.show', $screening) }}" class="text-brand-700 text-sm font-semibold">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $screenings->links() }}
        </div>
    @endif
</div>
@endsection
