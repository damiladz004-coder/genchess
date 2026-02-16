<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\School;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    private array $statusOptions = ['pending', 'active', 'disabled'];

    public function index()
    {
        $query = Classroom::query()->with('school');

        if (request('school_id')) {
            $query->where('school_id', request('school_id'));
        }

        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        $classes = $query->orderBy('name')->get();
        $schools = School::orderBy('school_name')->get(['id', 'school_name']);
        $statusOptions = $this->statusOptions;

        return view('admin.classes.index', compact('classes', 'schools', 'statusOptions'));
    }

    public function updateStatus(Request $request, Classroom $classroom)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', $this->statusOptions),
        ]);

        $classroom->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Class status updated.');
    }
}
