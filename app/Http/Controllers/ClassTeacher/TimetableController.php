<?php

namespace App\Http\Controllers\ClassTeacher;

use App\Http\Controllers\Controller;
use App\Models\SchoolTimetable;

class TimetableController extends Controller
{
    public function index()
    {
        $classTeachers = auth()->user()->classTeachers()->get();
        if ($classTeachers->isEmpty()) {
            abort(403);
        }

        $classIds = $classTeachers->pluck('class_id')->all();
        $entries = SchoolTimetable::whereIn('class_id', $classIds)
            ->where('status', 'approved')
            ->orderBy('class_id')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('class-teacher.timetable.index', compact('entries', 'classTeachers'));
    }
}
