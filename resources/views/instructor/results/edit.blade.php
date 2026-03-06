@extends('layouts.app')

@section('content')
<div class="max-w-4xl space-y-6">
    <h2 class="text-3xl gc-heading">Edit Student Result</h2>

    <form method="POST" action="{{ route('instructor.results.update', $result) }}" class="gc-panel p-4 space-y-4">
        @csrf
        @method('PATCH')
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Class</label>
                <select id="class_id" name="class_id" required>
                    <option value="">Select class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $result->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Student</label>
                <select id="student_id" name="student_id" required>
                    <option value="">Select student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" data-class-id="{{ $student->class_id }}" {{ old('student_id', $result->student_id) == $student->id ? 'selected' : '' }}>
                            {{ $student->first_name }} {{ $student->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <input type="text" name="term" value="{{ old('term', $result->term) }}" required>
            <input type="text" name="academic_session" value="{{ old('academic_session', $result->academic_session) }}" required>
        </div>

        <div class="grid md:grid-cols-3 gap-3">
            <input type="number" step="0.01" name="test_score" value="{{ old('test_score', $result->test_score) }}" placeholder="Test Score">
            <input type="number" step="0.01" name="test_max" value="{{ old('test_max', $result->test_max) }}" placeholder="Test Max">
            <div></div>
            <input type="number" step="0.01" name="practical_score" value="{{ old('practical_score', $result->practical_score) }}" placeholder="Practical Score">
            <input type="number" step="0.01" name="practical_max" value="{{ old('practical_max', $result->practical_max) }}" placeholder="Practical Max">
            <div></div>
            <input type="number" step="0.01" name="exam_score" value="{{ old('exam_score', $result->exam_score) }}" placeholder="Exam Score">
            <input type="number" step="0.01" name="exam_max" value="{{ old('exam_max', $result->exam_max) }}" placeholder="Exam Max">
            <select name="exam_mode" required>
                <option value="manual" {{ old('exam_mode', $result->exam_mode) === 'manual' ? 'selected' : '' }}>Manual</option>
                <option value="automatic" {{ old('exam_mode', $result->exam_mode) === 'automatic' ? 'selected' : '' }}>Automatic (Online)</option>
            </select>
        </div>

        <textarea name="instructor_comment" rows="3">{{ old('instructor_comment', $result->instructor_comment) }}</textarea>

        <div class="flex items-center gap-2">
            <button type="submit" class="gc-btn-primary">Update Result</button>
            <a href="{{ route('instructor.results.index') }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const classSelect = document.getElementById('class_id');
    const studentSelect = document.getElementById('student_id');
    const studentOptions = Array.from(studentSelect.querySelectorAll('option[data-class-id]'));

    function filterStudents() {
        const classId = classSelect.value;
        studentOptions.forEach((option) => {
            option.hidden = classId !== '' && option.dataset.classId !== classId;
        });
    }

    classSelect.addEventListener('change', filterStudents);
    filterStudents();
});
</script>
@endsection
