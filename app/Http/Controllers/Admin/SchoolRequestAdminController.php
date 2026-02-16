<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SchoolEnrollmentApproved;
use App\Models\School;
use App\Models\SchoolRequest;
use App\Models\User;
use App\Services\ClassGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SchoolRequestAdminController extends Controller
{
    public function index()
    {
        $requests = SchoolRequest::latest()->get();
        return view('admin.enrollments.index', compact('requests'));
    }

    public function show(SchoolRequest $schoolRequest)
    {
        return view('admin.enrollments.show', compact('schoolRequest'));
    }

    public function approve(SchoolRequest $schoolRequest)
    {
        $missing = [];
        foreach (['school_type', 'class_system', 'city', 'state'] as $field) {
            if (empty($schoolRequest->{$field})) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            return redirect()->back()->with('error', 'Missing required fields: ' . implode(', ', $missing));
        }

        if ($schoolRequest->status === 'approved' && $schoolRequest->school_id) {
            return redirect()->back()->with('info', 'This enrollment request is already approved.');
        }

        $password = null;

        try {
            DB::transaction(function () use ($schoolRequest, &$password) {
                $school = School::where('email', $schoolRequest->email)->first();

                if (!$school) {
                    $school = School::create([
                        'school_name' => $schoolRequest->school_name,
                        'school_type' => $schoolRequest->school_type,
                        'class_system' => $schoolRequest->class_system,
                        'address_line' => $schoolRequest->address_line,
                        'city' => $schoolRequest->city,
                        'state' => $schoolRequest->state,
                        'contact_person' => $schoolRequest->contact_person,
                        'email' => $schoolRequest->email,
                        'phone' => $schoolRequest->phone,
                        'status' => 'active',
                    ]);
                }

                $user = User::where('email', $schoolRequest->email)->first();
                if ($user && $user->role !== 'school_admin') {
                    throw new \RuntimeException('A user with this email already exists with a different role.');
                }

                if (!$user) {
                    $password = Str::random(10);
                    $user = User::create([
                        'name' => $schoolRequest->contact_person,
                        'email' => $schoolRequest->email,
                        'password' => Hash::make($password),
                        'role' => 'school_admin',
                        'school_id' => $school->id,
                    ]);
                } else {
                    $user->update([
                        'school_id' => $school->id,
                    ]);
                }

                ClassGenerator::generateForSchool($school);

                $schoolRequest->update([
                    'status' => 'approved',
                    'school_id' => $school->id,
                ]);
            });
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        Mail::to($schoolRequest->email)
            ->send(new SchoolEnrollmentApproved($schoolRequest, $password));

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment approved. School and School Admin created.');
    }
}
