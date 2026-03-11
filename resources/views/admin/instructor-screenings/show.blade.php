@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Screening Details</h2>
        <a href="{{ route('admin.instructor-screenings.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    <div class="gc-panel p-4 grid md:grid-cols-3 gap-4 text-sm">
        <div><span class="text-slate-500">Name:</span> {{ $screening->name }}</div>
        <div><span class="text-slate-500">Email:</span> {{ $screening->email }}</div>
        <div><span class="text-slate-500">Phone:</span> {{ $screening->phone ?? '-' }}</div>
        <div><span class="text-slate-500">Location:</span> {{ $screening->location ?? '-' }}</div>
        <div><span class="text-slate-500">Interview Mode:</span> {{ strtoupper($screening->interview_mode) }}</div>
        <div><span class="text-slate-500">Submitted:</span> {{ optional($screening->submitted_at)->format('Y-m-d H:i') }}</div>
        <div><span class="text-slate-500">Score:</span> {{ $screening->score }}/{{ $screening->total_questions }}</div>
        <div><span class="text-slate-500">Percentage:</span> {{ $screening->percentage }}%</div>
        <div>
            <span class="text-slate-500">Result:</span>
            @if($screening->passed)
                <span class="text-emerald-700 font-semibold">Passed</span>
            @else
                <span class="text-rose-700 font-semibold">Failed</span>
            @endif
        </div>
        <div><span class="text-slate-500">Stage 2 (Knowledge Interview):</span> {{ strtoupper($screening->stage_two_status) }}</div>
        <div><span class="text-slate-500">Stage 3 (Teaching Interview):</span> {{ strtoupper($screening->stage_three_status) }}</div>
        <div><span class="text-slate-500">Final Status:</span> {{ strtoupper(str_replace('_', ' ', $screening->final_status)) }}</div>
        <div><span class="text-slate-500">Certified At:</span> {{ optional($screening->certified_at)->format('Y-m-d H:i') ?? '-' }}</div>
        <div><span class="text-slate-500">Onboarded At:</span> {{ optional($screening->onboarded_at)->format('Y-m-d H:i') ?? '-' }}</div>
        <div><span class="text-slate-500">Instructor Profile:</span> {{ $screening->instructorProfile?->genchess_instructor_id ?? '-' }}</div>
    </div>

    <div class="gc-panel p-4">
        <h3 class="text-lg font-semibold mb-3">Workflow Update</h3>
        <form method="POST" action="{{ route('admin.instructor-screenings.workflow.update', $screening) }}" class="grid md:grid-cols-3 gap-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Stage 2 Status</label>
                <select name="stage_two_status">
                    <option value="pending" @selected($screening->stage_two_status === 'pending')>Pending</option>
                    <option value="passed" @selected($screening->stage_two_status === 'passed')>Passed</option>
                    <option value="failed" @selected($screening->stage_two_status === 'failed')>Failed</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Stage 3 Status</label>
                <select name="stage_three_status">
                    <option value="pending" @selected($screening->stage_three_status === 'pending')>Pending</option>
                    <option value="passed" @selected($screening->stage_three_status === 'passed')>Passed</option>
                    <option value="failed" @selected($screening->stage_three_status === 'failed')>Failed</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Final Status</label>
                <select name="final_status">
                    <option value="pending" @selected($screening->final_status === 'pending')>Pending</option>
                    <option value="approved" @selected($screening->final_status === 'approved')>Approved</option>
                    <option value="recommended_training" @selected($screening->final_status === 'recommended_training')>Recommended for Training</option>
                    <option value="rejected" @selected($screening->final_status === 'rejected')>Rejected</option>
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-slate-600 mb-1">Stage 2 Notes</label>
                <textarea name="stage_two_notes" rows="2">{{ old('stage_two_notes', $screening->stage_two_notes) }}</textarea>
            </div>

            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-slate-600 mb-1">Stage 3 Notes</label>
                <textarea name="stage_three_notes" rows="2">{{ old('stage_three_notes', $screening->stage_three_notes) }}</textarea>
            </div>

            <div class="md:col-span-3 flex flex-wrap items-center gap-3">
                <button type="submit" class="gc-btn-primary">Save Workflow</button>
                @if($screening->final_status === 'approved' && !$screening->instructorProfile)
                    <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('instructor.screening.biodata.create', ['screening' => $screening->id]) }}"
                       target="_blank"
                       class="gc-btn-secondary">
                        Open Biodata Link
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="gc-panel overflow-x-auto">
        <table>
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
                        <td>
                            @if(isset($answer['selected_label']))
                                {{ strtoupper($answer['selected_label']) }}. {{ $answer['selected_text'] ?? '-' }}
                            @else
                                {{ is_array($answer) ? '-' : (string) $answer }}
                            @endif
                        </td>
                        <td>
                            @if(isset($answer['correct_label']))
                                {{ strtoupper($answer['correct_label']) }}. {{ $answer['correct_text'] ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if(($answer['is_correct'] ?? false) === true)
                                <span class="text-emerald-700 font-semibold">Correct</span>
                            @elseif(isset($answer['is_correct']))
                                <span class="text-rose-700 font-semibold">Wrong</span>
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
