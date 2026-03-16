@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="gc-heading text-3xl">Community &amp; Home Consultation Requests</h2>
            <p class="text-sm text-slate-600">Review applicant details, request purpose, preferred meeting choices, and send meeting invitations.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="gc-panel border-emerald-200 bg-emerald-50 p-3 text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="gc-panel border-amber-200 bg-amber-50 p-3 text-amber-700">{{ session('warning') }}</div>
    @endif

    @if($consultations->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No consultation requests yet.</div>
    @else
        <div class="hidden overflow-x-auto gc-panel lg:block">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email / Phone</th>
                        <th>Location</th>
                        <th>Applicant Type</th>
                        <th>Purpose</th>
                        <th>Preferred Meeting</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consultations as $consultation)
                        <tr>
                            <td>{{ $consultation->name }}</td>
                            <td>
                                <div>{{ $consultation->email }}</div>
                                <div class="text-xs text-slate-500">{{ $consultation->phone }}</div>
                            </td>
                            <td>{{ $consultation->location }}</td>
                            <td>{{ $applicantTypeLabels[$consultation->applicant_type] ?? ucfirst((string) $consultation->applicant_type) }}</td>
                            <td>{{ $purposeLabels[$consultation->purpose] ?? ucfirst((string) $consultation->purpose) }}</td>
                            <td>
                                <div>{{ $meetingTypeLabels[$consultation->meeting_type] ?? str($consultation->meeting_type)->replace('_', ' ')->title() }}</div>
                                <div class="text-xs text-slate-500">
                                    {{ optional($consultation->preferred_date)->format('Y-m-d') }} · {{ $consultation->preferred_time?->format('H:i') }}
                                </div>
                            </td>
                            <td>{{ ucfirst($consultation->status) }}</td>
                            <td>
                                <a href="{{ route('admin.community-consultations.show', $consultation) }}" class="gc-btn-secondary px-3 py-1.5 text-xs">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid gap-4 lg:hidden">
            @foreach($consultations as $consultation)
                <article class="gc-panel p-4 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-slate-900">{{ $consultation->name }}</h3>
                            <p class="text-sm text-slate-500">{{ $applicantTypeLabels[$consultation->applicant_type] ?? ucfirst((string) $consultation->applicant_type) }}</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ ucfirst($consultation->status) }}</span>
                    </div>
                    <div class="grid gap-2 text-sm text-slate-700">
                        <div><span class="text-slate-500">Email:</span> {{ $consultation->email }}</div>
                        <div><span class="text-slate-500">Phone:</span> {{ $consultation->phone }}</div>
                        <div><span class="text-slate-500">Location:</span> {{ $consultation->location }}</div>
                        <div><span class="text-slate-500">Purpose:</span> {{ $purposeLabels[$consultation->purpose] ?? ucfirst((string) $consultation->purpose) }}</div>
                        <div><span class="text-slate-500">Preferred Meeting:</span> {{ $meetingTypeLabels[$consultation->meeting_type] ?? str($consultation->meeting_type)->replace('_', ' ')->title() }}</div>
                        <div><span class="text-slate-500">Preferred Date:</span> {{ optional($consultation->preferred_date)->format('Y-m-d') }}</div>
                        <div><span class="text-slate-500">Preferred Time:</span> {{ $consultation->preferred_time?->format('H:i') }}</div>
                    </div>
                    <a href="{{ route('admin.community-consultations.show', $consultation) }}" class="gc-btn-secondary">View</a>
                </article>
            @endforeach
        </div>

        {{ $consultations->links() }}
    @endif
</div>
@endsection
