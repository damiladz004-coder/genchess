<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacherFeedback;
use Illuminate\Http\Request;

class ClassTeacherFeedbackController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $feedback = $school->classTeacherFeedback()
            ->with(['classTeacher', 'classroom', 'instructor'])
            ->latest()
            ->get();

        return view('school.class-teacher-feedback.index', compact('feedback'));
    }

    public function create()
    {
        $school = auth()->user()->school;
        $classes = $school->classes()->orderBy('name')->get();
        $teachers = $school->classTeachers()->orderBy('name')->get();
        $instructors = $school->classes()
            ->with('instructors:id,name,email')
            ->get()
            ->pluck('instructors')
            ->flatten()
            ->unique('id')
            ->values();

        return view('school.class-teacher-feedback.create', compact('classes', 'teachers', 'instructors'));
    }

    public function store(Request $request)
    {
        $school = auth()->user()->school;

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'class_teacher_id' => 'required|exists:class_teachers,id',
            'instructor_id' => 'nullable|exists:users,id',
            'rating' => 'nullable|integer|min:1|max:5',
            'term' => 'nullable|string|max:50',
            'academic_year' => 'nullable|string|max:20',
            'comments' => 'required|string',
        ]);

        if (!$school->classes()->where('id', $request->class_id)->exists()) {
            return redirect()->back()->with('error', 'Selected class does not belong to your school.');
        }

        if (!$school->classTeachers()->where('id', $request->class_teacher_id)->exists()) {
            return redirect()->back()->with('error', 'Selected class teacher does not belong to your school.');
        }

        if ($request->instructor_id) {
            $instructorAttached = $school->classes()
                ->where('classes.id', $request->class_id)
                ->whereHas('instructors', function ($q) use ($request) {
                    $q->where('users.id', $request->instructor_id);
                })
                ->exists();

            if (!$instructorAttached) {
                return redirect()->back()->with('error', 'Selected instructor is not assigned to this class.');
            }
        }

        ClassTeacherFeedback::create([
            'school_id' => $school->id,
            'class_id' => $request->class_id,
            'class_teacher_id' => $request->class_teacher_id,
            'instructor_id' => $request->instructor_id,
            'rating' => $request->rating,
            'term' => $request->term,
            'academic_year' => $request->academic_year,
            'comments' => $request->comments,
        ]);

        return redirect()->route('school.class-teacher-feedback.index')
            ->with('success', 'Feedback submitted.');
    }
}
