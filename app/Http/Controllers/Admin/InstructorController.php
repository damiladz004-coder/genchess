<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InstructorInvite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class InstructorController extends Controller
{
    public function index()
    {
        $query = User::query()
            ->where('role', 'instructor')
            ->with(['teachingClasses.school'])
            ->orderBy('name');

        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        $instructors = $query->get();
        $statusOptions = ['active', 'suspended'];

        return view('admin.instructors.index', compact('instructors', 'statusOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'certification_level' => 'nullable|string|max:100',
            'status' => 'required|in:active,suspended',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $temporaryPassword = $request->password;

        $instructor = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'certification_level' => $request->certification_level,
            'status' => $request->status,
            'password' => Hash::make($temporaryPassword),
            'role' => 'instructor',
            'must_change_password' => true,
        ]);

        Mail::to($instructor->email)->send(new InstructorInvite($instructor, $temporaryPassword));

        return redirect()->back()->with('success', 'Instructor created.');
    }

    public function updateStatus(Request $request, User $instructor)
    {
        if ($instructor->role !== 'instructor') {
            abort(404);
        }

        $request->validate([
            'status' => 'required|in:active,suspended',
        ]);

        $instructor->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Instructor status updated.');
    }

    public function show(User $instructor)
    {
        if ($instructor->role !== 'instructor') {
            abort(404);
        }

        $instructor->load(['teachingClasses.school']);

        return view('admin.instructors.show', compact('instructor'));
    }

    public function sendResetLink(User $instructor)
    {
        if ($instructor->role !== 'instructor') {
            abort(404);
        }

        $status = Password::sendResetLink(['email' => $instructor->email]);

        return redirect()->back()->with(
            $status === Password::RESET_LINK_SENT ? 'success' : 'error',
            __($status)
        );
    }
}
