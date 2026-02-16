<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\TrainingEnrollment;

class TrainingController extends Controller
{
    public function index()
    {
        $enrollments = TrainingEnrollment::with(['cohort.course', 'certification'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('instructor.training.index', compact('enrollments'));
    }
}
