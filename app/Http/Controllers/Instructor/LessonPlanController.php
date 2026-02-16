<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\InstructorLessonPlan;
use App\Models\Classroom;
use Illuminate\Http\Request;

class LessonPlanController extends Controller
{
    public function index()
    {
        $instructorId = auth()->id();

        $query = InstructorLessonPlan::where('instructor_id', $instructorId)
            ->with('classroom');

        if (request('class_id')) {
            $query->where('class_id', request('class_id'));
        }

        if (request('from')) {
            $query->whereDate('lesson_date', '>=', request('from'));
        }

        if (request('to')) {
            $query->whereDate('lesson_date', '<=', request('to'));
        }

        $plans = $query->orderBy('lesson_date', 'desc')->get();
        $classes = auth()->user()->teachingClasses()->orderBy('name')->get();

        return view('instructor.lesson-plans.index', compact('plans', 'classes'));
    }

    public function create()
    {
        $classes = auth()->user()->teachingClasses()->orderBy('name')->get();

        return view('instructor.lesson-plans.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'lesson_date' => 'nullable|date',
            'topic' => 'required|string|max:255',
            'scheme_reference' => 'nullable|string|max:255',
            'objectives' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:planned,completed',
        ]);

        $classroom = Classroom::findOrFail($request->class_id);
        if (!$classroom->instructors->contains(auth()->id())) {
            abort(403);
        }

        InstructorLessonPlan::create([
            'instructor_id' => auth()->id(),
            'class_id' => $request->class_id,
            'lesson_date' => $request->lesson_date,
            'topic' => $request->topic,
            'scheme_reference' => $request->scheme_reference,
            'objectives' => $request->objectives,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        return redirect()->route('instructor.lesson-plans.index')
            ->with('success', 'Lesson plan created.');
    }

    public function edit(InstructorLessonPlan $lessonPlan)
    {
        if ($lessonPlan->instructor_id !== auth()->id()) {
            abort(403);
        }

        $classes = auth()->user()->teachingClasses()->orderBy('name')->get();

        return view('instructor.lesson-plans.edit', compact('lessonPlan', 'classes'));
    }

    public function update(Request $request, InstructorLessonPlan $lessonPlan)
    {
        if ($lessonPlan->instructor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'lesson_date' => 'nullable|date',
            'topic' => 'required|string|max:255',
            'scheme_reference' => 'nullable|string|max:255',
            'objectives' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:planned,completed',
        ]);

        $classroom = Classroom::findOrFail($request->class_id);
        if (!$classroom->instructors->contains(auth()->id())) {
            abort(403);
        }

        $lessonPlan->update([
            'class_id' => $request->class_id,
            'lesson_date' => $request->lesson_date,
            'topic' => $request->topic,
            'scheme_reference' => $request->scheme_reference,
            'objectives' => $request->objectives,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        return redirect()->route('instructor.lesson-plans.index')
            ->with('success', 'Lesson plan updated.');
    }

    public function destroy(InstructorLessonPlan $lessonPlan)
    {
        if ($lessonPlan->instructor_id !== auth()->id()) {
            abort(403);
        }

        $lessonPlan->delete();

        return redirect()->route('instructor.lesson-plans.index')
            ->with('success', 'Lesson plan deleted.');
    }
}
