@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="gc-heading text-3xl">Request Details</h2>
            <p class="text-sm text-slate-600">Review school onboarding or community/home consultation information.</p>
        </div>
        <a href="{{ route('admin.enrollments.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="gc-panel border-emerald-200 bg-emerald-50 p-3 text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="gc-panel border-amber-200 bg-amber-50 p-3 text-amber-700">{{ session('warning') }}</div>
    @endif
    @if(session('error'))
        <div class="gc-panel border-rose-200 bg-rose-50 p-3 text-rose-700">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="gc-panel border-sky-200 bg-sky-50 p-3 text-sky-700">{{ session('info') }}</div>
    @endif

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">School / Group</div><div class="mt-2 font-semibold text-slate-900">{{ $schoolRequest->school_name }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Program</div><div class="mt-2 font-semibold text-slate-900">{{ ucfirst($schoolRequest->program_type) }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Status</div><div class="mt-2 font-semibold text-slate-900">{{ ucfirst($schoolRequest->status) }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Portal / Consultation</div><div class="mt-2 text-sm text-slate-700">{{ $schoolRequest->portal_onboarded_at ? 'Portal onboarded '.$schoolRequest->portal_onboarded_at->format('Y-m-d H:i') : 'Not onboarded yet' }}</div></div>
    </div>

    <div class="gc-panel p-4 text-sm text-slate-700">
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            <div><span class="text-slate-500">Contact Person:</span> {{ $schoolRequest->contact_person }}</div>
            <div><span class="text-slate-500">Email:</span> {{ $schoolRequest->email }}</div>
            <div><span class="text-slate-500">Phone / WhatsApp:</span> {{ $schoolRequest->phone }}</div>
            <div><span class="text-slate-500">School Type:</span> {{ $schoolRequest->school_type ? ucfirst($schoolRequest->school_type) : 'N/A' }}</div>
            <div><span class="text-slate-500">Class System:</span> {{ $schoolRequest->class_system ?? 'N/A' }}</div>
            <div><span class="text-slate-500">Address:</span> {{ $schoolRequest->address_line ?? 'N/A' }}</div>
            <div><span class="text-slate-500">City:</span> {{ $schoolRequest->city ?? 'N/A' }}</div>
            <div><span class="text-slate-500">State:</span> {{ $schoolRequest->state ?? 'N/A' }}</div>
            <div><span class="text-slate-500">Message:</span> {{ $schoolRequest->message ?? 'None' }}</div>
        </div>
    </div>

    @if($schoolRequest->status === 'pending')
        <form method="POST" action="{{ route('admin.enrollments.approve', $schoolRequest) }}">
            @csrf
            <button type="submit" class="gc-btn-primary">Approve Request</button>
        </form>
    @endif

    @if(in_array(strtolower((string) $schoolRequest->program_type), ['community', 'home'], true) && $schoolRequest->status === 'approved')
        <div class="gc-panel p-4">
            <h3 class="mb-4 text-lg font-semibold">Schedule Consultation</h3>
            <form method="POST" action="{{ route('admin.enrollments.consultation', $schoolRequest) }}" class="grid gap-4 md:grid-cols-2">
                @csrf
                @method('PATCH')
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Type</label>
                    <select name="meeting_type" required>
                        <option value="zoom" @selected($schoolRequest->meeting_type === 'zoom')>Zoom</option>
                        <option value="google_meet" @selected($schoolRequest->meeting_type === 'google_meet')>Google Meet</option>
                        <option value="physical" @selected($schoolRequest->meeting_type === 'physical')>Physical</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Link</label>
                    <input type="url" name="consultation_link" value="{{ old('consultation_link', $schoolRequest->consultation_link) }}">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Date</label>
                    <input type="date" name="meeting_date" value="{{ old('meeting_date', optional($schoolRequest->meeting_date)->format('Y-m-d')) }}" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Time</label>
                    <input type="time" name="meeting_time" value="{{ old('meeting_time', $schoolRequest->meeting_time?->format('H:i')) }}" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Meeting ID</label>
                    <input type="text" name="consultation_meeting_id" value="{{ old('consultation_meeting_id', $schoolRequest->consultation_meeting_id) }}">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Passcode</label>
                    <input type="text" name="consultation_passcode" value="{{ old('consultation_passcode', $schoolRequest->consultation_passcode) }}">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="gc-btn-primary">Send Consultation Invite</button>
                </div>
            </form>
        </div>
    @endif

    @if(!in_array(strtolower((string) $schoolRequest->program_type), ['community', 'home'], true) && $schoolRequest->status === 'approved')
        <div class="gc-panel p-4 text-sm text-slate-700">
            <h3 class="mb-3 text-lg font-semibold">School Portal Onboarding</h3>
            <p>Email link sent: {{ optional($schoolRequest->portal_link_sent_at)->format('Y-m-d H:i') ?? 'No' }}</p>
            <p>WhatsApp link sent: {{ optional($schoolRequest->portal_whatsapp_sent_at)->format('Y-m-d H:i') ?? 'No' }}</p>
            <p>Portal completed: {{ optional($schoolRequest->portal_onboarded_at)->format('Y-m-d H:i') ?? 'No' }}</p>
        </div>
    @endif
</div>
@endsection
