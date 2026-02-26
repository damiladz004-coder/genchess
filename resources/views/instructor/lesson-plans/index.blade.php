@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Lesson Plans</h2>
        <a href="{{ route('instructor.lesson-plans.create') }}" class="gc-btn-primary">New Plan</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="gc-panel p-4">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
                <select name="class_id">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">From</label>
                <input type="date" name="from" value="{{ request('from') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">To</label>
                <input type="date" name="to" value="{{ request('to') }}">
            </div>
        </div>
        <div class="mt-3 flex gap-2">
            <button type="submit" class="gc-btn-primary">Filter</button>
            <a href="{{ route('instructor.lesson-plans.index') }}" class="gc-btn-secondary">Reset</a>
        </div>
    </form>

    @if($plans->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No lesson plans yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Class</th>
                        <th>Topic</th>
                        <th>Scheme Ref</th>
                        <th>Resources</th>
                        <th>WIPPEA</th>
                        <th>Review Status</th>
                        <th>Admin Feedback</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                        <tr>
                            <td>{{ $plan->lesson_date?->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ $plan->classroom->name ?? 'N/A' }}</td>
                            <td class="font-medium text-slate-800">{{ $plan->topic }}</td>
                            <td>{{ $plan->scheme_reference ?? '-' }}</td>
                            <td>
                                @php
                                    $hasResources = filled($plan->materials_required)
                                        || filled($plan->resource_text_content)
                                        || !empty($plan->resource_links)
                                        || !empty($plan->resource_files);
                                @endphp
                                {{ $hasResources ? 'Yes' : 'No' }}
                            </td>
                            <td>
                                @php
                                    $hasWippea = filled($plan->wippea_warm_up)
                                        || filled($plan->wippea_introduction)
                                        || filled($plan->wippea_presentation)
                                        || filled($plan->wippea_practice)
                                        || filled($plan->wippea_evaluation)
                                        || filled($plan->wippea_application);
                                @endphp
                                {{ $hasWippea ? 'Yes' : 'No' }}
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $plan->review_status ?? 'draft')) }}</td>
                            <td class="max-w-xs">
                                {{ $plan->review_feedback ?? '-' }}
                            </td>
                            <td>{{ ucfirst($plan->status) }}</td>
                            <td class="flex items-center gap-3">
                                @if(in_array($plan->review_status, ['draft', 'changes_requested'], true))
                                    <form method="POST" action="{{ route('instructor.lesson-plans.submit', $plan) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-emerald-700 text-sm font-semibold">
                                            {{ $plan->review_status === 'changes_requested' ? 'Resubmit' : 'Submit for Review' }}
                                        </button>
                                    </form>
                                @elseif($plan->review_status === 'submitted')
                                    <span class="text-amber-700 text-sm font-semibold">Awaiting Review</span>
                                @elseif($plan->review_status === 'approved')
                                    <span class="text-emerald-700 text-sm font-semibold">Approved</span>
                                @endif
                                <a href="{{ route('instructor.lesson-plans.edit', $plan) }}" class="text-brand-700 text-sm font-semibold">Edit</a>
                                <form method="POST" action="{{ route('instructor.lesson-plans.destroy', $plan) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 text-sm font-semibold" onclick="return confirm('Delete this plan?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
