@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="gc-heading text-3xl">Consultation Request Details</h2>
            <p class="text-sm text-slate-600">Review the applicant, purpose of request, and schedule the meeting invitation.</p>
        </div>
        <a href="{{ route('admin.community-consultations.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="gc-panel border-emerald-200 bg-emerald-50 p-3 text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="gc-panel border-amber-200 bg-amber-50 p-3 text-amber-700">{{ session('warning') }}</div>
    @endif
    @if ($errors->any())
        <div class="gc-panel border-rose-200 bg-rose-50 p-3 text-rose-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Applicant</div><div class="mt-2 font-semibold text-slate-900">{{ $communityConsultation->name }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Applicant Type</div><div class="mt-2 text-sm text-slate-900">{{ $applicantTypeLabels[$communityConsultation->applicant_type] ?? ucfirst((string) $communityConsultation->applicant_type) }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Purpose</div><div class="mt-2 text-sm text-slate-900">{{ $purposeLabels[$communityConsultation->purpose] ?? ucfirst((string) $communityConsultation->purpose) }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Status</div><div class="mt-2 font-semibold text-slate-900">{{ ucfirst($communityConsultation->status) }}</div></div>
    </div>

    <div class="gc-panel p-4 text-sm text-slate-700">
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            <div><span class="text-slate-500">Email:</span> {{ $communityConsultation->email }}</div>
            <div><span class="text-slate-500">Phone / WhatsApp:</span> {{ $communityConsultation->phone }}</div>
            <div><span class="text-slate-500">Location:</span> {{ $communityConsultation->location }}</div>
            <div><span class="text-slate-500">Preferred Meeting Type:</span> {{ $meetingTypeLabels[$communityConsultation->meeting_type] ?? str($communityConsultation->meeting_type)->replace('_', ' ')->title() }}</div>
            <div><span class="text-slate-500">Preferred Date:</span> {{ optional($communityConsultation->preferred_date)->format('Y-m-d') ?? 'N/A' }}</div>
            <div><span class="text-slate-500">Preferred Time:</span> {{ $communityConsultation->preferred_time?->format('H:i') ?? 'N/A' }}</div>
            <div><span class="text-slate-500">Scheduled At:</span> {{ optional($communityConsultation->scheduled_at)->format('Y-m-d H:i') ?? 'Not scheduled yet' }}</div>
            <div><span class="text-slate-500">Confirmation Email:</span> {{ optional($communityConsultation->confirmation_sent_at)->format('Y-m-d H:i') ?? 'Not sent' }}</div>
            <div><span class="text-slate-500">Invitation Email:</span> {{ optional($communityConsultation->invitation_sent_at)->format('Y-m-d H:i') ?? 'Not sent' }}</div>
            <div class="md:col-span-2 xl:col-span-3"><span class="text-slate-500">Message:</span> {{ $communityConsultation->message ?: 'None provided' }}</div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <div class="gc-panel p-4">
            <h3 class="mb-4 text-lg font-semibold">Schedule Meeting Invitation</h3>
            <form method="POST" action="{{ route('admin.community-consultations.schedule', $communityConsultation) }}" class="grid gap-4 md:grid-cols-2">
                @csrf
                @method('PATCH')
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Type</label>
                    <select name="meeting_type" required>
                        @foreach ($meetingTypeLabels as $value => $label)
                            <option value="{{ $value }}" @selected(old('meeting_type', $communityConsultation->meeting_type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Date and Time</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($communityConsultation->scheduled_at)->format('Y-m-d\\TH:i')) }}" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Link</label>
                    <input type="url" name="meeting_link" value="{{ old('meeting_link', $communityConsultation->meeting_link) }}">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Meeting ID</label>
                    <input type="text" name="meeting_id" value="{{ old('meeting_id', $communityConsultation->meeting_id) }}">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Passcode</label>
                    <input type="text" name="meeting_passcode" value="{{ old('meeting_passcode', $communityConsultation->meeting_passcode) }}">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Location</label>
                    <input type="text" name="meeting_location" value="{{ old('meeting_location', $communityConsultation->meeting_location) }}">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Status After Scheduling</label>
                    <select name="status">
                        <option value="scheduled" @selected(old('status', $communityConsultation->status) === 'scheduled')>Scheduled</option>
                        <option value="completed" @selected(old('status', $communityConsultation->status) === 'completed')>Completed</option>
                        <option value="pending" @selected(old('status', $communityConsultation->status) === 'pending')>Pending</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="gc-btn-primary">Save and Send Invitation</button>
                </div>
            </form>
        </div>

        <div class="gc-panel p-4">
            <h3 class="mb-4 text-lg font-semibold">Update Status</h3>
            <form method="POST" action="{{ route('admin.community-consultations.status', $communityConsultation) }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Consultation Status</label>
                    <select name="status" required>
                        <option value="pending" @selected($communityConsultation->status === 'pending')>Pending</option>
                        <option value="scheduled" @selected($communityConsultation->status === 'scheduled')>Scheduled</option>
                        <option value="completed" @selected($communityConsultation->status === 'completed')>Completed</option>
                    </select>
                </div>
                <button type="submit" class="gc-btn-secondary">Update Status</button>
            </form>
        </div>
    </div>
</div>
@endsection
