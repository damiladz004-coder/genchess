@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="gc-heading text-3xl">Screening Details</h2>
            <p class="text-sm text-slate-600">Review stage one results, schedule interviews, and issue onboarding access.</p>
        </div>
        <a href="{{ route('admin.instructor-screenings.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if($errors->any())
        <div class="gc-panel border-rose-200 bg-rose-50 p-4 text-rose-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="gc-panel border-emerald-200 bg-emerald-50 p-3 text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Candidate</div><div class="mt-2 font-semibold text-slate-900">{{ $screening->name }}</div><div class="text-sm text-slate-500">{{ $screening->email }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Stage 1 Result</div><div class="mt-2 text-2xl font-bold {{ $screening->passed ? 'text-emerald-700' : 'text-rose-700' }}">{{ $screening->percentage }}%</div><div class="text-sm text-slate-500">{{ $screening->score }}/{{ $screening->total_questions }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Preferred Slot</div><div class="mt-2 font-semibold text-slate-900">{{ optional($screening->preferred_interview_date)->format('M d, Y') ?? 'Not set' }}</div><div class="text-sm text-slate-500">{{ $screening->preferred_interview_time?->format('g:i A') ?? 'No time selected' }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Final Status</div><div class="mt-2 font-semibold text-slate-900">{{ strtoupper(str_replace('_', ' ', $screening->final_status)) }}</div><div class="text-sm text-slate-500">{{ $screening->onboarding_link_sent_at ? 'Onboarding sent '.$screening->onboarding_link_sent_at->format('M d, Y H:i') : 'Onboarding not yet sent' }}</div></div>
    </div>

    <div class="gc-panel p-4 text-sm text-slate-700">
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            <div><span class="text-slate-500">Phone:</span> {{ $screening->phone ?? '-' }}</div>
            <div><span class="text-slate-500">Location:</span> {{ $screening->location ?? '-' }}</div>
            <div><span class="text-slate-500">Interview Mode:</span> {{ strtoupper($screening->interview_mode) }}</div>
            <div><span class="text-slate-500">Submitted:</span> {{ optional($screening->submitted_at)->format('Y-m-d H:i') }}</div>
            <div><span class="text-slate-500">Preferred Notes:</span> {{ $screening->preferred_interview_notes ?? '-' }}</div>
            <div><span class="text-slate-500">Instructor Profile:</span> {{ $screening->instructorProfile?->genchess_instructor_id ?? '-' }}</div>
        </div>
    </div>

    <div class="gc-panel p-4">
        <h3 class="mb-4 text-lg font-semibold">Workflow, Scheduling, and Approval</h3>
        <form method="POST" action="{{ route('admin.instructor-screenings.workflow.update', $screening) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Stage 2 Status</label>
                    <select name="stage_two_status">
                        <option value="pending" @selected($screening->stage_two_status === 'pending')>Pending</option>
                        <option value="passed" @selected($screening->stage_two_status === 'passed')>Passed</option>
                        <option value="failed" @selected($screening->stage_two_status === 'failed')>Failed</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Stage 3 Status</label>
                    <select name="stage_three_status">
                        <option value="pending" @selected($screening->stage_three_status === 'pending')>Pending</option>
                        <option value="passed" @selected($screening->stage_three_status === 'passed')>Passed</option>
                        <option value="failed" @selected($screening->stage_three_status === 'failed')>Failed</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Final Status</label>
                    <select name="final_status">
                        <option value="pending" @selected($screening->final_status === 'pending')>Pending</option>
                        <option value="approved" @selected($screening->final_status === 'approved')>Approved</option>
                        <option value="recommended_training" @selected($screening->final_status === 'recommended_training')>Recommended for Training</option>
                        <option value="rejected" @selected($screening->final_status === 'rejected')>Rejected</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-2">
                <section class="rounded-2xl border border-slate-200 p-4">
                    <h4 class="font-semibold text-slate-900">Stage 2 Interview</h4>
                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Type</label>
                            <select name="stage_two_meeting_type">
                                <option value="">Select</option>
                                <option value="zoom" @selected($screening->stage_two_meeting_type === 'zoom')>Zoom</option>
                                <option value="google_meet" @selected($screening->stage_two_meeting_type === 'google_meet')>Google Meet</option>
                                <option value="physical" @selected($screening->stage_two_meeting_type === 'physical')>Physical</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Link</label>
                            <input type="url" name="stage_two_meeting_link" value="{{ old('stage_two_meeting_link', $screening->stage_two_meeting_link) }}">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Meeting ID</label>
                            <input type="text" name="stage_two_meeting_id" value="{{ old('stage_two_meeting_id', $screening->stage_two_meeting_id) }}">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Passcode</label>
                            <input type="text" name="stage_two_passcode" value="{{ old('stage_two_passcode', $screening->stage_two_passcode) }}">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Date</label>
                            <input type="date" name="stage_two_meeting_date" value="{{ old('stage_two_meeting_date', optional($screening->stage_two_meeting_date)->format('Y-m-d')) }}">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Time</label>
                            <input type="time" name="stage_two_meeting_time" value="{{ old('stage_two_meeting_time', $screening->stage_two_meeting_time?->format('H:i')) }}">
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-slate-500">Email sent: {{ optional($screening->stage_two_invitation_sent_at)->format('Y-m-d H:i') ?? 'No' }}. WhatsApp sent: {{ optional($screening->stage_two_whatsapp_sent_at)->format('Y-m-d H:i') ?? 'No' }}</div>
                </section>

                <section class="rounded-2xl border border-slate-200 p-4">
                    <h4 class="font-semibold text-slate-900">Stage 3 Interview</h4>
                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Type</label>
                            <select name="stage_three_meeting_type">
                                <option value="">Select</option>
                                <option value="zoom" @selected($screening->stage_three_meeting_type === 'zoom')>Zoom</option>
                                <option value="google_meet" @selected($screening->stage_three_meeting_type === 'google_meet')>Google Meet</option>
                                <option value="physical" @selected($screening->stage_three_meeting_type === 'physical')>Physical</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Link</label>
                            <input type="url" name="stage_three_meeting_link" value="{{ old('stage_three_meeting_link', $screening->stage_three_meeting_link) }}">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Meeting ID</label>
                            <input type="text" name="stage_three_meeting_id" value="{{ old('stage_three_meeting_id', $screening->stage_three_meeting_id) }}">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Passcode</label>
                            <input type="text" name="stage_three_passcode" value="{{ old('stage_three_passcode', $screening->stage_three_passcode) }}">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Date</label>
                            <input type="date" name="stage_three_meeting_date" value="{{ old('stage_three_meeting_date', optional($screening->stage_three_meeting_date)->format('Y-m-d')) }}">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-600">Meeting Time</label>
                            <input type="time" name="stage_three_meeting_time" value="{{ old('stage_three_meeting_time', $screening->stage_three_meeting_time?->format('H:i')) }}">
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-slate-500">Email sent: {{ optional($screening->stage_three_invitation_sent_at)->format('Y-m-d H:i') ?? 'No' }}. WhatsApp sent: {{ optional($screening->stage_three_whatsapp_sent_at)->format('Y-m-d H:i') ?? 'No' }}</div>
                </section>
            </div>

            <div class="grid gap-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Stage 2 Notes</label>
                    <textarea name="stage_two_notes" rows="3">{{ old('stage_two_notes', $screening->stage_two_notes) }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Stage 3 Notes</label>
                    <textarea name="stage_three_notes" rows="3">{{ old('stage_three_notes', $screening->stage_three_notes) }}</textarea>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" class="gc-btn-primary">Save Workflow</button>
                @if($screening->final_status === 'approved' && !$screening->instructorProfile)
                    <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('instructor.screening.biodata.create', ['screening' => $screening->id]) }}" target="_blank" class="gc-btn-secondary">Open Onboarding Link</a>
                @endif
            </div>
        </form>
    </div>

    <div class="gc-panel overflow-x-auto">
        <table class="gc-table min-w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Section</th>
                    <th>Question</th>
                    <th>Selected</th>
                    <th>Correct</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($answers as $answer)
                    <tr>
                        <td>{{ $answer['question_id'] ?? '-' }}</td>
                        <td>{{ $answer['section'] ?? '-' }}</td>
                        <td>{{ $answer['prompt'] ?? '-' }}</td>
                        <td>{{ isset($answer['selected_label']) ? strtoupper($answer['selected_label']).'. '.($answer['selected_text'] ?? '-') : '-' }}</td>
                        <td>{{ isset($answer['correct_label']) ? strtoupper($answer['correct_label']).'. '.($answer['correct_text'] ?? '-') : '-' }}</td>
                        <td>
                            @if(($answer['is_correct'] ?? false) === true)
                                <span class="font-semibold text-emerald-700">Correct</span>
                            @elseif(isset($answer['is_correct']))
                                <span class="font-semibold text-rose-700">Wrong</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
