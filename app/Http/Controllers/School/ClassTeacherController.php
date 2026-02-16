<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacher;
use App\Mail\ClassTeacherInvite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClassTeacherController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $teachers = $school->classTeachers()
            ->with('classroom')
            ->orderBy('name')
            ->get();

        return view('school.class-teachers.index', compact('teachers'));
    }

    public function create()
    {
        $school = auth()->user()->school;
        $classes = $school->classes()->orderBy('name')->get();

        return view('school.class-teachers.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $school = auth()->user()->school;

        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        if (!$school->classes()->where('id', $request->class_id)->exists()) {
            return redirect()->back()->with('error', 'Selected class does not belong to your school.');
        }

        $classTeacher = ClassTeacher::create([
            'school_id' => $school->id,
            'class_id' => $request->class_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        if ($request->email) {
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser && $existingUser->role !== 'class_teacher') {
                return redirect()->back()->with('error', 'A user with this email already exists with a different role.');
            }

            $password = null;
            $user = $existingUser;
            if (!$user) {
                $password = Str::random(10);
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($password),
                    'role' => 'class_teacher',
                    'must_change_password' => true,
                ]);
            }

        if ($classTeacher) {
            $classTeacher->update(['user_id' => $user->id]);
            if ($password) {
                Mail::to($request->email)->send(new ClassTeacherInvite($classTeacher, $password));
            }
        }
    }

        return redirect()->route('school.class-teachers.index')
            ->with('success', 'Class teacher added successfully.');
    }

    public function edit(ClassTeacher $classTeacher)
    {
        $school = auth()->user()->school;

        if ($classTeacher->school_id !== $school->id) {
            abort(403);
        }

        $classes = $school->classes()->orderBy('name')->get();

        return view('school.class-teachers.edit', compact('classTeacher', 'classes'));
    }

    public function update(Request $request, ClassTeacher $classTeacher)
    {
        $school = auth()->user()->school;

        if ($classTeacher->school_id !== $school->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        if (!$school->classes()->where('id', $request->class_id)->exists()) {
            return redirect()->back()->with('error', 'Selected class does not belong to your school.');
        }

        $classTeacher->update([
            'class_id' => $request->class_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        return redirect()->route('school.class-teachers.index')
            ->with('success', 'Class teacher updated successfully.');
    }

    public function destroy(ClassTeacher $classTeacher)
    {
        $school = auth()->user()->school;

        if ($classTeacher->school_id !== $school->id) {
            abort(403);
        }

        $classTeacher->delete();

        return redirect()->route('school.class-teachers.index')
            ->with('success', 'Class teacher removed.');
    }

    public function updateStatus(Request $request, ClassTeacher $classTeacher)
    {
        $school = auth()->user()->school;

        if ($classTeacher->school_id !== $school->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $classTeacher->update([
            'status' => $request->status,
        ]);

        return redirect()->route('school.class-teachers.index')
            ->with('success', 'Class teacher status updated.');
    }
}
