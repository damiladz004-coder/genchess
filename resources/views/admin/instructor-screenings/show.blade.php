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
