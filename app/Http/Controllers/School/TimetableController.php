<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Mail\TimetableSubmitted;
use App\Models\SchoolTimetable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TimetableController extends Controller
{
    private array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public function index()
    {
        $school = auth()->user()->school;
        $timetables = $school->timetables()
            ->with('classroom')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
        $classes = $school->classes()->orderBy('name')->get();

        return view('school.timetables.index', compact('timetables', 'classes'));
    }

    public function create()
    {
        $school = auth()->user()->school;
        $classes = $school->classes()->orderBy('name')->get();
        $days = $this->days;

        return view('school.timetables.create', compact('classes', 'days'));
    }

    public function store(Request $request)
    {
        $school = auth()->user()->school;

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'day_of_week' => 'required|in:' . implode(',', $this->days),
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!$school->classes()->where('id', $request->class_id)->exists()) {
            return redirect()->back()->with('error', 'Selected class does not belong to your school.');
        }

        SchoolTimetable::create([
            'school_id' => $school->id,
            'class_id' => $request->class_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => 'draft',
        ]);

        return redirect()->route('school.timetables.index')->with('success', 'Timetable entry created.');
    }

    public function edit(SchoolTimetable $timetable)
    {
        $school = auth()->user()->school;
        if ($timetable->school_id !== $school->id) {
            abort(403);
        }

        if (!in_array($timetable->status, ['draft', 'changes_requested'], true)) {
            return redirect()->back()->with('error', 'Only draft or returned entries can be edited.');
        }

        $classes = $school->classes()->orderBy('name')->get();
        $days = $this->days;

        return view('school.timetables.edit', compact('timetable', 'classes', 'days'));
    }

    public function update(Request $request, SchoolTimetable $timetable)
    {
        $school = auth()->user()->school;
        if ($timetable->school_id !== $school->id) {
            abort(403);
        }

        if (!in_array($timetable->status, ['draft', 'changes_requested'], true)) {
            return redirect()->back()->with('error', 'Only draft or returned entries can be edited.');
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'day_of_week' => 'required|in:' . implode(',', $this->days),
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!$school->classes()->where('id', $request->class_id)->exists()) {
            return redirect()->back()->with('error', 'Selected class does not belong to your school.');
        }

        $timetable->update([
            'class_id' => $request->class_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => 'draft',
            'review_comment' => null,
            'reviewed_at' => null,
        ]);

        return redirect()->route('school.timetables.index')->with('success', 'Timetable entry updated.');
    }

    public function submit(SchoolTimetable $timetable)
    {
        $school = auth()->user()->school;
        if ($timetable->school_id !== $school->id) {
            abort(403);
        }

        if (!in_array($timetable->status, ['draft', 'changes_requested'], true)) {
            return redirect()->back()->with('error', 'This entry cannot be submitted.');
        }

        $timetable->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $superAdmins = User::where('role', 'super_admin')->pluck('email')->filter()->all();
        if (!empty($superAdmins)) {
            Mail::to($superAdmins)->send(new TimetableSubmitted($timetable->load(['school', 'classroom'])));
        }

        return redirect()->back()->with('success', 'Timetable entry submitted for approval.');
    }
}
