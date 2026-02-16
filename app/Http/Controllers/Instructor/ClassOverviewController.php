<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\InstructorTimetable;

class ClassOverviewController extends Controller
{
    public function index()
    {
        $instructor = auth()->user();
        $classes = $instructor->teachingClasses()
            ->with('school')
            ->orderBy('name')
            ->get();

        $entries = InstructorTimetable::where('instructor_id', $instructor->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $schedule = [];
        foreach ($entries as $entry) {
            $schedule[$entry->class_id][] = trim(($entry->day_of_week ?? '') . ' ' . ($entry->start_time ?? '') . '-' . ($entry->end_time ?? ''));
        }

        return view('instructor.classes.index', compact('classes', 'schedule'));
    }
}
