@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-2xl">
    <h2 class="text-3xl gc-heading">Activate Exam for Class</h2>

    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('school.exams.assignments.store') }}" class="gc-panel p-4 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Exam Template</label>
            <select name="exam_template_id" id="exam_template_id" required>
                <option value="">Select template</option>
                @foreach($templates as $template)
                    <option value="{{ $template->id }}" data-class-id="{{ $template->class_id }}">
                        {{ $template->title }} ({{ $template->classroom->name ?? 'N/A' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
            <select name="class_id" id="class_id" required>
                <option value="">Select class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Term</label>
                <select name="term" required>
                    <option value="">Select term</option>
                    @foreach($terms as $term)
                        <option value="{{ $term }}">{{ $term }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Session</label>
                <select name="session" required>
                    <option value="">Select session</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session }}">{{ $session }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Mode</label>
                <select name="mode" required>
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                    <option value="manual">Manual</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Exam Date (Optional)</label>
                <input type="date" name="exam_date">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
            <select name="status" required>
                <option value="draft">Draft</option>
                <option value="active" selected>Active</option>
                <option value="closed">Closed</option>
            </select>
            <p class="text-xs text-slate-500 mt-1">Set to Active when students should be able to write the exam.</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="gc-btn-primary">Assign</button>
            <a href="{{ route('school.exams.assignments.index') }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const classSelect = document.getElementById('class_id');
    const templateSelect = document.getElementById('exam_template_id');
    if (!classSelect || !templateSelect) return;

    const options = Array.from(templateSelect.querySelectorAll('option[data-class-id]'));
    const filterTemplates = () => {
        const selectedClassId = classSelect.value;
        templateSelect.value = '';
        options.forEach((option) => {
            option.hidden = selectedClassId !== '' && option.dataset.classId !== selectedClassId;
        });
    };

    classSelect.addEventListener('change', filterTemplates);
    filterTemplates();
});
</script>
@endsection
