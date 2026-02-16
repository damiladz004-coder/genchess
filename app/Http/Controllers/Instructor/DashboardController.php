<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\InstructorLessonPlan;
class DashboardController extends Controller
{
    public function index()
    {
        $classes = auth()
            ->user()
            ->teachingClasses()
            ->withCount('students')
            ->orderBy('name')
            ->get();

        $upcomingLessons = InstructorLessonPlan::where('instructor_id', auth()->id())
            ->whereDate('lesson_date', '>=', now()->toDateString())
            ->orderBy('lesson_date')
            ->limit(5)
            ->get();

        $attendanceSummary = Attendance::where('marked_by', auth()->id())
            ->whereDate('date', '>=', now()->subDays(7)->toDateString())
            ->selectRaw("status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('instructor.dashboard', compact('classes', 'upcomingLessons', 'attendanceSummary'));
    }
}
