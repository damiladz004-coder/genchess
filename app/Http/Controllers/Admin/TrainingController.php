<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\TrainingCohort;
use App\Models\TrainingCourse;
use App\Models\TrainingEnrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    public function index()
    {
        $courses = TrainingCourse::orderBy('title')->get();
        $cohorts = TrainingCohort::with('course')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.training.index', compact('courses', 'cohorts'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_weeks' => 'required|integer|min:1|max:52',
        ]);

        TrainingCourse::create([
            'title' => $request->title,
            'description' => $request->description,
            'duration_weeks' => $request->duration_weeks,
            'active' => true,
        ]);

        return redirect()->back()->with('success', 'Course created.');
    }

    public function storeCohort(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:training_courses,id',
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,ongoing,completed',
        ]);

        TrainingCohort::create($request->only([
            'course_id',
            'name',
            'start_date',
            'end_date',
            'status',
        ]));

        return redirect()->back()->with('success', 'Cohort created.');
    }

    public function showCohort(TrainingCohort $cohort)
    {
        $cohort->load(['course', 'enrollments.user', 'enrollments.certification']);
        $instructors = User::where('role', 'instructor')->orderBy('name')->get();

        return view('admin.training.cohort', compact('cohort', 'instructors'));
    }

    public function enroll(Request $request, TrainingCohort $cohort)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        TrainingEnrollment::firstOrCreate([
            'cohort_id' => $cohort->id,
            'user_id' => $request->user_id,
        ]);

        return redirect()->back()->with('success', 'Instructor enrolled.');
    }

    public function updateEnrollment(Request $request, TrainingEnrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:enrolled,completed,dropped',
        ]);

        $completedAt = $request->status === 'completed' ? now() : null;

        $enrollment->update([
            'status' => $request->status,
            'completed_at' => $completedAt,
        ]);

        return redirect()->back()->with('success', 'Enrollment updated.');
    }

    public function issueCertificate(TrainingEnrollment $enrollment)
    {
        if ($enrollment->certification) {
            return redirect()->back()->with('success', 'Certificate already issued.');
        }

        Certification::create([
            'enrollment_id' => $enrollment->id,
            'certificate_code' => strtoupper(Str::random(10)),
            'issued_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Certificate issued.');
    }
}
