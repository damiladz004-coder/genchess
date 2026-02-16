<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\SchoolPayment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        $totalClasses = Classroom::where('school_id', $schoolId)->count();
        $totalStudents = Student::where('school_id', $schoolId)->count();

        $assignedInstructorCount = User::query()
            ->where('role', 'instructor')
            ->whereHas('teachingClasses', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->distinct('users.id')
            ->count('users.id');

        $payment = SchoolPayment::where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->first();

        $totalDue = SchoolPayment::where('school_id', $schoolId)->sum('total_due');
        $totalPaid = SchoolPayment::where('school_id', $schoolId)->sum('amount_paid');
        $outstandingBalance = max(0, $totalDue - $totalPaid);

        return view('school.dashboard', compact(
            'totalClasses',
            'totalStudents',
            'assignedInstructorCount',
            'outstandingBalance',
            'payment'
        ));
    }
}
