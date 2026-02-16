<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\School;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $query = Student::query()->with(['school', 'class']);

        if (request('school_id')) {
            $query->where('school_id', request('school_id'));
        }

        if (request('class_id')) {
            $query->where('class_id', request('class_id'));
        }

        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        if (request('q')) {
            $q = request('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('admission_number', 'like', "%{$q}%")
                    ->orWhereHas('school', function ($sq) use ($q) {
                        $sq->where('school_name', 'like', "%{$q}%");
                    });
            });
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')->get();
        $schools = School::orderBy('school_name')->get(['id', 'school_name']);
        $classes = Classroom::orderBy('name')->get(['id', 'name', 'school_id']);
        $statusOptions = ['pending', 'approved', 'rejected'];

        return view('admin.students.index', compact('students', 'schools', 'classes', 'statusOptions'));
    }

    public function approve(Student $student)
    {
        $student->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Student approved.');
    }

    public function reject(Student $student)
    {
        $student->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Student rejected.');
    }

    public function move(Request $request, Student $student)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $classroom = Classroom::findOrFail($request->class_id);
        if ($classroom->school_id !== $student->school_id) {
            return redirect()->back()->with('error', 'Class must belong to the same school.');
        }

        $student->update(['class_id' => $request->class_id]);

        return redirect()->back()->with('success', 'Student moved to new class.');
    }
}
