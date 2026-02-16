<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TimetableApproved;
use App\Mail\TimetableChangesRequested;
use App\Models\School;
use App\Models\SchoolTimetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TimetableReviewController extends Controller
{
    public function index()
    {
        $query = SchoolTimetable::query()->with(['school', 'classroom']);

        if (request('school_id')) {
            $query->where('school_id', request('school_id'));
        }

        $status = request('status', 'submitted');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $timetables = $query->orderBy('submitted_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $schools = School::orderBy('school_name')->get(['id', 'school_name']);
        $statusOptions = ['draft', 'submitted', 'approved', 'changes_requested'];

        return view('admin.timetables.index', compact('timetables', 'schools', 'statusOptions', 'status'));
    }

    public function approve(SchoolTimetable $timetable)
    {
        if ($timetable->status !== 'submitted') {
            return redirect()->back()->with('error', 'Only submitted entries can be approved.');
        }

        $timetable->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'review_comment' => null,
        ]);

        $timetable->load(['school', 'classroom']);
        if ($timetable->school && $timetable->school->email) {
            Mail::to($timetable->school->email)->send(new TimetableApproved($timetable));
        }

        return redirect()->back()->with('success', 'Timetable entry approved.');
    }

    public function requestChanges(Request $request, SchoolTimetable $timetable)
    {
        if ($timetable->status !== 'submitted') {
            return redirect()->back()->with('error', 'Only submitted entries can be returned for changes.');
        }

        $request->validate([
            'review_comment' => 'required|string|max:1000',
        ]);

        $timetable->update([
            'status' => 'changes_requested',
            'reviewed_at' => now(),
            'review_comment' => $request->review_comment,
        ]);

        $timetable->load(['school', 'classroom']);
        if ($timetable->school && $timetable->school->email) {
            Mail::to($timetable->school->email)->send(new TimetableChangesRequested($timetable));
        }

        return redirect()->back()->with('success', 'Changes requested.');
    }
}
