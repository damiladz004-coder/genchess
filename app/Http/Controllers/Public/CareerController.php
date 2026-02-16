<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::where('active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('careers', compact('jobs'));
    }

    public function show(JobPosting $job)
    {
        return view('careers-show', compact('job'));
    }

    public function apply(Request $request, JobPosting $job)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:30',
            'cover_letter' => 'nullable|string|max:2000',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:4096',
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('careers', 'public');
        }

        JobApplication::create([
            'job_posting_id' => $job->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cover_letter' => $request->cover_letter,
            'cv_path' => $cvPath,
            'status' => 'new',
        ]);

        return redirect()
            ->route('careers.show', $job)
            ->with('success', 'Application submitted successfully.');
    }
}
