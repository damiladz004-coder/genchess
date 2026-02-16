<?php

namespace App\Http\Controllers\ClassTeacher;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $classTeachers = auth()->user()->classTeachers()->with(['classroom.school'])->get();
        if ($classTeachers->isEmpty()) {
            abort(403);
        }
        return view('class-teacher.dashboard', compact('classTeachers'));
    }
}
