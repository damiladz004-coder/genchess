<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolRequest;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolPayment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $requestsByMonth = SchoolRequest::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->pluck('total', 'month')
            ->toArray();

        $recentApprovals = School::query()
            ->where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->limit(8)
            ->get();

        $statusCounts = School::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $totalStudents = Student::count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalOutstanding = max(0, SchoolPayment::sum('total_due') - SchoolPayment::sum('amount_paid'));
        $schoolsByState = School::query()
            ->select('state', DB::raw('COUNT(*) as total'))
            ->groupBy('state')
            ->orderBy('state')
            ->get();

        return view('admin.dashboard', [
            'totalSchools'   => School::count(),
            'pendingSchools' => $statusCounts['pending'] ?? 0,
            'activeSchools'  => $statusCounts['active'] ?? 0,
            'statusCounts'   => $statusCounts,
            'requestsByMonth' => $requestsByMonth,
            'recentApprovals' => $recentApprovals,
            'totalStudents' => $totalStudents,
            'totalInstructors' => $totalInstructors,
            'totalOutstanding' => $totalOutstanding,
            'schoolsByState' => $schoolsByState,
        ]);
    }
}
