<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\InstructorTimetable;
use App\Models\User;

class InstructorController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        $instructors = User::query()
            ->where('role', 'instructor')
            ->whereHas('teachingClasses', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with(['teachingClasses' => function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $timetables = InstructorTimetable::query()
            ->whereHas('classroom', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with(['classroom', 'instructor'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('school.instructors.index', compact('instructors', 'timetables'));
    }
}
