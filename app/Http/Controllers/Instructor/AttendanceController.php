<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function selectClass()
    {
        $classes = auth()->user()->teachingClasses()->orderBy('name')->get();
        $entries = \App\Models\InstructorTimetable::where('instructor_id', auth()->id())
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $schedule = [];
        foreach ($entries as $entry) {
            $schedule[$entry->class_id][] = trim(($entry->day_of_week ?? '') . ' ' . ($entry->start_time ?? '') . '-' . ($entry->end_time ?? ''));
        }

        return view('instructor.attendance.select', compact('classes', 'schedule'));
    }

    public function index(Classroom $classroom)
    {
        // Ensure instructor is assigned to this class
        if (!$classroom->instructors->contains(auth()->id())) {
            abort(403);
        }

        $students = Student::where('class_id', $classroom->id)->get();
        $date = request('date', now()->toDateString());
        $existing = Attendance::where('class_id', $classroom->id)
            ->whereDate('date', $date)
            ->pluck('status', 'student_id')
            ->toArray();

        return view('instructor.attendance.index', compact('classroom', 'students', 'date', 'existing'));
    }

    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classroom->id,
                    'date' => $request->date,
                ],
                [
                    'status' => $status,
                    'school_id' => auth()->user()->school_id,
                    'marked_by' => auth()->id(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance saved');
    }
}
