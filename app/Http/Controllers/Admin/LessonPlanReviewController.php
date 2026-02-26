<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorLessonPlan;
use App\Models\User;
use Illuminate\Http\Request;

class LessonPlanReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = InstructorLessonPlan::with(['instructor:id,name,email', 'classroom:id,name', 'reviewer:id,name'])
            ->orderByRaw("CASE review_status WHEN 'submitted' THEN 0 WHEN 'changes_requested' THEN 1 WHEN 'draft' THEN 2 WHEN 'approved' THEN 3 ELSE 4 END")
            ->orderByDesc('updated_at');

        if ($request->filled('review_status')) {
            $query->where('review_status', $request->review_status);
        }

        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', (int) $request->instructor_id);
        }

        $plans = $query->get();
        $instructors = User::where('role', 'instructor')->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.lesson-plans.index', compact('plans', 'instructors'));
    }

    public function review(Request $request, InstructorLessonPlan $lessonPlan)
    {
        if ($lessonPlan->review_status !== 'submitted') {
            return back()->withErrors(['review' => 'Only submitted lesson plans can be reviewed.']);
        }

        $data = $request->validate([
            'decision' => 'required|in:approved,changes_requested',
            'review_feedback' => 'nullable|string',
        ]);

        if ($data['decision'] === 'changes_requested' && blank($data['review_feedback'] ?? null)) {
            return back()->withErrors(['review_feedback' => 'Please provide a reason for requesting changes.']);
        }

        $lessonPlan->update([
            'review_status' => $data['decision'],
            'review_feedback' => $data['review_feedback'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Lesson plan review saved.');
    }
}
