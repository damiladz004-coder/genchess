<?php

namespace App\Http\Controllers\ClassTeacher;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacherFeedback;
use App\Models\Classroom;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create()
    {
        $classTeachers = auth()->user()->classTeachers()->get();
        if ($classTeachers->isEmpty()) {
            abort(403);
        }

        $classIds = $classTeachers->pluck('class_id')->all();
        $classrooms = Classroom::with('instructors')
            ->whereIn('id', $classIds)
            ->get();

        $feedback = ClassTeacherFeedback::whereIn('class_id', $classIds)
            ->whereIn('class_teacher_id', $classTeachers->pluck('id')->all())
            ->with(['classroom', 'instructor'])
            ->latest()
            ->get();

        return view('class-teacher.feedback.create', compact('classTeachers', 'classrooms', 'feedback'));
    }

    public function store(Request $request)
    {
        $classTeachers = auth()->user()->classTeachers()->get();
        if ($classTeachers->isEmpty()) {
            abort(403);
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'instructor_id' => 'nullable|exists:users,id',
            'rating' => 'nullable|integer|min:1|max:5',
            'comments' => 'required|string',
            'term' => 'nullable|string|max:50',
            'academic_year' => 'nullable|string|max:20',
        ]);

        $classIds = $classTeachers->pluck('class_id')->all();
        if (!in_array((int) $request->class_id, $classIds, true)) {
            return redirect()->back()->with('error', 'Selected class is not assigned to you.');
        }

        $classTeacher = $classTeachers->firstWhere('class_id', (int) $request->class_id);
        if (!$classTeacher) {
            return redirect()->back()->with('error', 'No class teacher record found for the selected class.');
        }

        $classroom = Classroom::with('instructors')->find($request->class_id);
        if ($request->instructor_id) {
            $instructorAttached = $classroom && $classroom->instructors->contains('id', $request->instructor_id);
            if (!$instructorAttached) {
                return redirect()->back()->with('error', 'Selected instructor is not assigned to this class.');
            }
        }

        ClassTeacherFeedback::create([
            'school_id' => $classTeacher->school_id,
            'class_id' => $request->class_id,
            'class_teacher_id' => $classTeacher->id,
            'instructor_id' => $request->instructor_id,
            'rating' => $request->rating,
            'term' => $request->term,
            'academic_year' => $request->academic_year,
            'comments' => $request->comments,
        ]);

        return redirect()->back()->with('success', 'Feedback submitted.');
    }
}
