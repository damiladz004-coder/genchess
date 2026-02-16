<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacherFeedback;

class ClassTeacherFeedbackController extends Controller
{
    public function index()
    {
        $query = ClassTeacherFeedback::query()
            ->with(['school', 'classTeacher', 'classroom', 'instructor'])
            ->latest();

        $schoolId = request('school_id');
        $classId = request('class_id');
        $term = request('term');
        $academicYear = request('academic_year');

        if (!empty($schoolId)) {
            $query->where('school_id', $schoolId);
        }

        if (!empty($classId)) {
            $query->where('class_id', $classId);
        }

        if (!empty($term)) {
            $query->where('term', $term);
        }

        if (!empty($academicYear)) {
            $query->where('academic_year', $academicYear);
        }

        $feedback = $query->get();

        $schools = \App\Models\School::orderBy('school_name')->get(['id', 'school_name']);
        $classes = \App\Models\Classroom::orderBy('name')->get(['id', 'name']);
        $terms = ClassTeacherFeedback::query()
            ->select('term')
            ->whereNotNull('term')
            ->distinct()
            ->orderBy('term')
            ->pluck('term');
        $academicYears = ClassTeacherFeedback::query()
            ->select('academic_year')
            ->whereNotNull('academic_year')
            ->distinct()
            ->orderBy('academic_year')
            ->pluck('academic_year');

        return view('admin.class-teacher-feedback.index', compact(
            'feedback',
            'schools',
            'classes',
            'terms',
            'academicYears'
        ));
    }
}
