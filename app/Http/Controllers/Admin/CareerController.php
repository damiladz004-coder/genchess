<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CareerController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::orderBy('created_at', 'desc')->get();
        $applications = JobApplication::with('job')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.careers.index', compact('jobs', 'applications'));
    }

    public function storeJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);

        JobPosting::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'location' => $request->location,
            'type' => $request->type,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'active' => $request->active ?? true,
        ]);

        return redirect()->back()->with('success', 'Job posted.');
    }

    public function updateApplication(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:new,reviewed,interview,accepted,rejected',
            'notes' => 'nullable|string|max:2000',
        ]);

        $application->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Application updated.');
    }

    public function downloadCv(JobApplication $application)
    {
        if (!$application->cv_path || !Storage::disk('public')->exists($application->cv_path)) {
            return redirect()->back()->withErrors(['cv' => 'CV not found.']);
        }

        return Storage::disk('public')->download($application->cv_path);
    }
}
