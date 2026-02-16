<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom;

class ClassController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $classes = $school->classes;

        return view('school.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('school.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'level' => 'required|in:primary,jss,ss',
            'chess_mode' => 'required|in:subject,club',
        ]);

        Classroom::create([
            'name' => $request->name,
            'level' => $request->level,
            'chess_mode' => $request->chess_mode,
            'school_id' => Auth::user()->school_id,
            'status' => 'pending',
        ]);

        return redirect()->route('school.classes.index')
            ->with('success', 'Class created successfully');
    }

    public function edit(Classroom $classroom)
    {
        $school = Auth::user()->school;
        if ($classroom->school_id !== $school->id) {
            abort(403);
        }

        return view('school.classes.edit', compact('classroom'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $school = Auth::user()->school;
        if ($classroom->school_id !== $school->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string',
            'level' => 'required|in:primary,jss,ss',
            'chess_mode' => 'required|in:subject,club',
        ]);

        $classroom->update([
            'name' => $request->name,
            'level' => $request->level,
            'chess_mode' => $request->chess_mode,
        ]);

        return redirect()->route('school.classes.index')
            ->with('success', 'Class updated successfully');
    }
}
