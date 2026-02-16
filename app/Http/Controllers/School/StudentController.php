<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $query = Student::where('school_id', $school->id)->with('class');

        if (request('class_id')) {
            $query->where('class_id', request('class_id'));
        }

        if (request('q')) {
            $q = request('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('admission_number', 'like', "%{$q}%");
            });
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')->get();
        $classes = $school->classes()->orderBy('name')->get();

        return view('school.students.index', compact('students', 'classes'));
    }

    public function bulkUploadForm()
    {
        $school = Auth::user()->school;
        $classes = $school->classes()->orderBy('name')->get();

        return view('school.students.bulk-upload', compact('classes'));
    }

    public function bulkUploadStore(Request $request)
    {
        $school = Auth::user()->school;

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return redirect()->back()->with('error', 'Unable to read the file.');
        }

        $header = fgetcsv($handle);
        $created = 0;
        $skipped = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 5) {
                $skipped++;
                continue;
            }

            [$firstName, $lastName, $gender, $admissionNumber, $classId] = $row;

            $firstName = trim($firstName);
            $lastName = trim($lastName);
            $gender = strtolower(trim($gender));
            $admissionNumber = trim($admissionNumber);
            $classId = (int) trim($classId);

            if (!$firstName || !$lastName || !in_array($gender, ['male', 'female'], true)) {
                $skipped++;
                continue;
            }

            if (!$school->classes()->where('id', $classId)->exists()) {
                $skipped++;
                continue;
            }

            Student::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'gender' => $gender,
                'class_id' => $classId,
                'school_id' => $school->id,
                'admission_number' => $admissionNumber ?: null,
                'status' => 'pending',
            ]);

            $created++;
        }

        fclose($handle);

        return redirect()->route('school.students.index')
            ->with('success', "Bulk upload complete. Created: {$created}, Skipped: {$skipped}.");
    }

    public function create()
    {
        $school = Auth::user()->school;
        $classes = $school->classes;

        return view('school.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'class_id' => 'required|exists:classes,id',
            'admission_number' => 'nullable|string|max:50',
        ]);

        if (!auth()->user()->school->classes()->where('id', $request->class_id)->exists()) {
            return redirect()->back()->with('error', 'Selected class does not belong to your school.');
        }

        Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
            'school_id' => auth()->user()->school_id,
            'admission_number' => $request->admission_number,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('school.students.index')
            ->with('success', 'Student added successfully');
    }

    public function edit(Student $student)
    {
        $school = Auth::user()->school;

        if ($student->school_id !== $school->id) {
            abort(403);
        }

        $classes = $school->classes()->orderBy('name')->get();

        return view('school.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $school = Auth::user()->school;

        if ($student->school_id !== $school->id) {
            abort(403);
        }

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'class_id' => 'required|exists:classes,id',
            'admission_number' => 'nullable|string|max:50',
        ]);

        if (!$school->classes()->where('id', $request->class_id)->exists()) {
            return redirect()->back()->with('error', 'Selected class does not belong to your school.');
        }

        $student->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
            'admission_number' => $request->admission_number,
        ]);

        return redirect()
            ->route('school.students.index')
            ->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student)
    {
        $school = Auth::user()->school;

        if ($student->school_id !== $school->id) {
            abort(403);
        }

        $student->delete();

        return redirect()
            ->route('school.students.index')
            ->with('success', 'Student removed');
    }
}
