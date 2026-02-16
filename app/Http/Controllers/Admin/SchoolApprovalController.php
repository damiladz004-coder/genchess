<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Services\ClassGenerator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolApprovalController extends Controller
{
    private array $statusOptions = ['pending', 'active', 'suspended'];

    public function index()
    {
        $status = request('status', 'pending');

        $query = School::query();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $schools = $query->orderBy('school_name')->get();
        $statuses = School::query()
            ->select('status')
            ->distinct()
            ->pluck('status')
            ->filter()
            ->values()
            ->toArray();

        $statusOptions = $this->statusOptions;

        return view('admin.schools.index', compact('schools', 'status', 'statuses', 'statusOptions'));
    }

    public function create()
    {
        $statusOptions = $this->statusOptions;

        return view('admin.schools.create', compact('statusOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_type' => 'required|in:private,public',
            'class_system' => 'required|in:primary_jss_ss,grade_1_12,year_1_12',
            'address_line' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => ['required', 'string', 'max:100', Rule::in(config('nigeria.states', []))],
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:schools,email',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:' . implode(',', $this->statusOptions),
        ]);

        School::create($request->only([
            'school_name',
            'school_type',
            'class_system',
            'address_line',
            'city',
            'state',
            'contact_person',
            'email',
            'phone',
            'status',
        ]));

        return redirect()
            ->route('admin.schools.index')
            ->with('success', 'School created successfully.');
    }

    public function approve($id)
    {
        $school = School::findOrFail($id);

        // 1️⃣ Activate school
        $school->update(['status' => 'active']);

        // 2️⃣ Create School Admin user
        $password = Str::random(10);

        $user = User::create([
            'name'      => $school->contact_person,
            'email'     => $school->email,
            'password'  => Hash::make($password),
            'role'      => 'school_admin',
            'school_id' => $school->id,
        ]);

        // 3️⃣ Auto-generate classes
        ClassGenerator::generateForSchool($school);

        // (Email sending comes later)

        return redirect()
            ->back()
            ->with('success', 'School approved and School Admin created.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', $this->statusOptions),
        ]);

        $school = School::findOrFail($id);
        $school->update(['status' => $request->status]);

        return redirect()
            ->back()
            ->with('success', 'School status updated.');
    }

}
