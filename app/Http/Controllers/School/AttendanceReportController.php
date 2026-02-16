<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class AttendanceReportController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;

        $classes = Classroom::where('school_id', $schoolId)->get();

        return view('school.attendance.report', compact('classes'));
    }

    public function show(Classroom $classroom)
    {
        $class = $classroom->load(['students', 'attendances']);

        $attendances = $class->attendances()
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('date');

        return view('school.attendance.report', compact('class', 'attendances'));
    }

    public function summary(Classroom $classroom)
    {
        $students = $classroom->students;

        $summary = [];

        foreach ($students as $student) {
            $total = Attendance::where('student_id', $student->id)->count();

            $present = Attendance::where('student_id', $student->id)
                ->where('status', 'present')
                ->count();

            $absent = Attendance::where('student_id', $student->id)
                ->where('status', 'absent')
                ->count();

            $percentage = $total > 0
                ? round(($present / $total) * 100, 1)
                : 0;

            $summary[] = [
                'student' => $student,
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'percentage' => $percentage,
            ];
        }

        return view('school.attendance.summary', compact('classroom', 'summary'));
    }
}
