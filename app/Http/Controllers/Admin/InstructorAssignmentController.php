<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;

class InstructorAssignmentController extends Controller
{
    public function index()
    {
        $classes = Classroom::query()
            ->with(['school', 'instructors'])
            ->orderBy('school_id')
            ->orderBy('name')
            ->get();

        $instructors = User::query()
            ->where('role', 'instructor')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.instructor-assignments.index', compact('classes', 'instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'instructor_id' => 'required|exists:users,id',
        ]);

        $instructor = User::where('id', $request->instructor_id)
            ->where('role', 'instructor')
            ->first();

        if (!$instructor) {
            return redirect()->back()->with('error', 'Selected user is not an instructor.');
        }

        $classroom = Classroom::findOrFail($request->class_id);
        $classroom->instructors()->syncWithoutDetaching([$instructor->id]);

        return redirect()->back()->with('success', 'Instructor assigned to class.');
    }

    public function destroy(Classroom $classroom, User $instructor)
    {
        if ($instructor->role !== 'instructor') {
            return redirect()->back()->with('error', 'Selected user is not an instructor.');
        }

        $classroom->instructors()->detach($instructor->id);

        return redirect()->back()->with('success', 'Instructor removed from class.');
    }
}
