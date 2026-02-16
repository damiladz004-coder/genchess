<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $classes = auth()->user()
            ->teachingClasses()
            ->with('school')
            ->orderBy('name')
            ->get();

        return view('instructor.assignments.index', compact('classes'));
    }

    public function destroy(Classroom $classroom)
    {
        $user = auth()->user();

        if (!$classroom->instructors()->where('users.id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'You are not assigned to this class.');
        }

        $classroom->instructors()->detach($user->id);

        return redirect()->back()->with('success', 'Assignment removed.');
    }
}
