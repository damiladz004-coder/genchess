<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\InstructorTimetable;
use App\Models\Classroom;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    private array $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    public function index()
    {
        $query = InstructorTimetable::where('instructor_id', auth()->id())
            ->with('classroom')
            ->orderBy('day_of_week')
            ->orderBy('start_time');

        if (request('class_id')) {
            $query->where('class_id', request('class_id'));
        }

        if (request('day')) {
            $query->where('day_of_week', request('day'));
        }

        $timetable = $query->get();
        $classes = auth()->user()->teachingClasses()->orderBy('name')->get();
        if (request('class_id')) {
            $classes = $classes->where('id', (int) request('class_id'));
        }

        $schedule = [];
        foreach ($timetable as $entry) {
            $schedule[$entry->class_id][$entry->day_of_week][] = trim(($entry->start_time ?? '') . '-' . ($entry->end_time ?? ''));
        }

        return view('instructor.timetable.index', [
            'timetable' => $timetable,
            'days' => $this->days,
            'classes' => $classes,
            'schedule' => $schedule,
        ]);
    }

    public function create()
    {
        $classes = auth()->user()->teachingClasses()->orderBy('name')->get();

        return view('instructor.timetable.create', [
            'classes' => $classes,
            'days' => $this->days,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'day_of_week' => 'required|in:' . implode(',', $this->days),
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $classroom = Classroom::findOrFail($request->class_id);
        if (!$classroom->instructors->contains(auth()->id())) {
            abort(403);
        }

        InstructorTimetable::create([
            'instructor_id' => auth()->id(),
            'class_id' => $request->class_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'notes' => $request->notes,
        ]);

        return redirect()->route('instructor.timetable.index')
            ->with('success', 'Timetable entry added.');
    }

    public function edit(InstructorTimetable $timetable)
    {
        if ($timetable->instructor_id !== auth()->id()) {
            abort(403);
        }

        $classes = auth()->user()->teachingClasses()->orderBy('name')->get();

        return view('instructor.timetable.edit', [
            'timetable' => $timetable,
            'classes' => $classes,
            'days' => $this->days,
        ]);
    }

    public function update(Request $request, InstructorTimetable $timetable)
    {
        if ($timetable->instructor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'day_of_week' => 'required|in:' . implode(',', $this->days),
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $classroom = Classroom::findOrFail($request->class_id);
        if (!$classroom->instructors->contains(auth()->id())) {
            abort(403);
        }

        $timetable->update([
            'class_id' => $request->class_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'notes' => $request->notes,
        ]);

        return redirect()->route('instructor.timetable.index')
            ->with('success', 'Timetable entry updated.');
    }

    public function destroy(InstructorTimetable $timetable)
    {
        if ($timetable->instructor_id !== auth()->id()) {
            abort(403);
        }

        $timetable->delete();

        return redirect()->route('instructor.timetable.index')
            ->with('success', 'Timetable entry removed.');
    }
}
