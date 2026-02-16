<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Classroom $class)
    {
        $user = auth()->user();

        // ❌ Block access if class is not in instructor's school
        if ($class->school_id !== $user->school_id) {
            abort(403, 'Unauthorized access to this class.');
        }

        $students = $class->students;

        return view('school.attendance.index', compact('class', 'students'));
    }

    public function create($classId)
    {
        $students = Student::where('class_id', $classId)->get();

        return view('school.attendance.create', compact('students', 'classId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        $schoolId = Auth::user()->school_id;
        $markedBy = Auth::id();

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $request->class_id,
                    'date' => $request->date,
                ],
                [
                    'school_id' => $schoolId,
                    'status' => $status,
                    'marked_by' => $markedBy,
                ]
            );
        }

        return redirect()
            ->route('school.attendance.index', $request->class_id)
            ->with('success', 'Attendance saved successfully.');
    }
}
