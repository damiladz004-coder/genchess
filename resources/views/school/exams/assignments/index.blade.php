@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Exam Assignments</h2>
        <div class="flex items-center gap-2">
            <form id="bulkDeleteForm" method="POST" action="{{ route('school.exams.assignments.bulk-destroy') }}" onsubmit="return confirm('Delete selected exam assignments? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="gc-btn-secondary text-rose-700">Delete Selected</button>
            </form>
            <a href="{{ route('school.exams.assignments.create') }}" class="gc-btn-primary">Activate/Assign Exam</a>
        </div>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">{{ session('error') }}</div>
    @endif

    @if($assignments->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No exam assignments yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="select_all_assignments">
                        </th>
                        <th>Template</th>
                        <th>Class</th>
                        <th>Term</th>
                        <th>Session</th>
                        <th>Mode</th>
                        <th>Status</th>
                        <th>Exam Code</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                        <tr>
                            <td>
                                <input type="checkbox" class="assignment-checkbox" form="bulkDeleteForm" name="assignment_ids[]" value="{{ $assignment->id }}">
                            </td>
                            <td>{{ $assignment->template->title ?? 'N/A' }}</td>
                            <td>{{ $assignment->classroom->name ?? 'N/A' }}</td>
                            <td>{{ $assignment->term }}</td>
                            <td>{{ $assignment->session }}</td>
                            <td>{{ ucfirst($assignment->mode) }}</td>
                            <td>{{ ucfirst($assignment->status) }}</td>
                            <td>{{ $assignment->exam_code ?? '-' }}</td>
                            <td>{{ $assignment->exam_date ?? '-' }}</td>
                            <td class="space-x-3">
                                <a class="text-brand-700 underline" href="{{ route('school.exams.assignments.results', $assignment) }}">Results</a>
                                @if($assignment->mode !== 'online')
                                    <a class="text-brand-700 underline" href="{{ route('school.exams.assignments.print', $assignment) }}">Print</a>
                                @else
                                    @if($assignment->exam_code && $assignment->status === 'active')
                                        <a class="text-brand-700 underline" href="{{ route('public.exams.take', $assignment->exam_code) }}" target="_blank">Student Link</a>
                                    @endif
                                @endif
                                <form method="POST" action="{{ route('school.exams.assignments.status', $assignment) }}" style="display:inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" style="font-size:12px;">
                                        <option value="draft" {{ $assignment->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="active" {{ $assignment->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="closed" {{ $assignment->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </form>
                                <form method="POST" action="{{ route('school.exams.assignments.destroy', $assignment) }}" style="display:inline-block;" onsubmit="return confirm('Delete this exam assignment? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-700 underline text-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select_all_assignments');
    const checkboxes = document.querySelectorAll('.assignment-checkbox');
    if (!selectAll || checkboxes.length === 0) return;

    selectAll.addEventListener('change', function () {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAll.checked;
        });
    });
});
</script>
@endsection
