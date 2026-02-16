<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\School;
use App\Models\SchoolPayment;
use App\Models\Student;
use App\Models\User;

class ReportsController extends Controller
{
    public function index()
    {
        $schools = School::orderBy('school_name')->get(['id', 'school_name', 'state']);
        $terms = ['Term 1', 'Term 2', 'Term 3'];

        $studentsQuery = School::withCount('students')
            ->orderBy('school_name')
            ->select('id', 'school_name', 'state');

        if (request('school_id')) {
            $studentsQuery->where('id', request('school_id'));
        }

        $studentsPerSchool = $studentsQuery->get();

        $paymentsQuery = SchoolPayment::query()
            ->selectRaw('school_id, SUM(total_due) as total_due, SUM(amount_paid) as total_paid')
            ->groupBy('school_id');

        if (request('school_id')) {
            $paymentsQuery->where('school_id', request('school_id'));
        }
        if (request('term')) {
            $paymentsQuery->where('term', request('term'));
        }

        $paymentsPerSchool = $paymentsQuery->get()->keyBy('school_id');

        $instructorQuery = User::query()
            ->where('role', 'instructor')
            ->withCount('teachingClasses')
            ->orderBy('name');

        if (request('school_id')) {
            $schoolId = (int) request('school_id');
            $instructorQuery->whereHas('teachingClasses', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            });
        }

        $instructorWorkload = $instructorQuery->get(['id', 'name', 'email']);

        $schoolsByState = $studentsPerSchool->groupBy('state')
            ->map(fn ($items) => $items->count())
            ->sortKeys();

        return view('admin.reports.index', compact(
            'schools',
            'studentsPerSchool',
            'paymentsPerSchool',
            'instructorWorkload',
            'schoolsByState',
            'terms'
        ));
    }

    public function exportStudents()
    {
        $studentsQuery = School::withCount('students')
            ->orderBy('school_name')
            ->select('id', 'school_name');

        if (request('school_id')) {
            $studentsQuery->where('id', request('school_id'));
        }

        $rows = $studentsQuery->get()
            ->map(function ($school) {
                return [
                    'school' => $school->school_name,
                    'students' => $school->students_count,
                ];
            });

        return $this->csv('students_per_school.csv', ['school', 'students'], $rows);
    }

    public function exportPayments()
    {
        $paymentsQuery = SchoolPayment::query()
            ->selectRaw('school_id, SUM(total_due) as total_due, SUM(amount_paid) as total_paid')
            ->groupBy('school_id');

        if (request('school_id')) {
            $paymentsQuery->where('school_id', request('school_id'));
        }
        if (request('term')) {
            $paymentsQuery->where('term', request('term'));
        }

        $paymentsPerSchool = $paymentsQuery->get()->keyBy('school_id');

        $schoolsQuery = School::orderBy('school_name')->get(['id', 'school_name']);
        if (request('school_id')) {
            $schoolsQuery = $schoolsQuery->where('id', (int) request('school_id'));
        }

        $rows = $schoolsQuery
            ->map(function ($school) use ($paymentsPerSchool) {
                $payment = $paymentsPerSchool[$school->id] ?? null;
                $due = $payment->total_due ?? 0;
                $paid = $payment->total_paid ?? 0;
                $outstanding = max(0, $due - $paid);
                return [
                    'school' => $school->school_name,
                    'total_due' => $due,
                    'total_paid' => $paid,
                    'outstanding' => $outstanding,
                ];
            });

        return $this->csv('payments_per_school.csv', ['school', 'total_due', 'total_paid', 'outstanding'], $rows);
    }

    public function exportWorkload()
    {
        $instructorQuery = User::query()
            ->where('role', 'instructor')
            ->withCount('teachingClasses')
            ->orderBy('name');

        if (request('school_id')) {
            $schoolId = (int) request('school_id');
            $instructorQuery->whereHas('teachingClasses', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            });
        }

        $rows = $instructorQuery->get(['id', 'name', 'email'])
            ->map(function ($instructor) {
                return [
                    'instructor' => $instructor->name,
                    'classes' => $instructor->teaching_classes_count,
                ];
            });

        return $this->csv('instructor_workload.csv', ['instructor', 'classes'], $rows);
    }

    public function exportStates()
    {
        $rows = School::orderBy('state')
            ->when(request('school_id'), fn ($q) => $q->where('id', request('school_id')))
            ->get(['state'])
            ->groupBy('state')
            ->map(fn ($items, $state) => [
                'state' => $state,
                'count' => $items->count(),
            ])
            ->values();

        return $this->csv('schools_by_state.csv', ['state', 'count'], $rows);
    }

    public function exportAll()
    {
        $studentsQuery = School::withCount('students')
            ->orderBy('school_name')
            ->select('id', 'school_name');

        if (request('school_id')) {
            $studentsQuery->where('id', request('school_id'));
        }

        $students = $studentsQuery->get()
            ->map(fn ($school) => [
                'school' => $school->school_name,
                'students' => $school->students_count,
            ]);

        $paymentsQuery = SchoolPayment::query()
            ->selectRaw('school_id, SUM(total_due) as total_due, SUM(amount_paid) as total_paid')
            ->groupBy('school_id');

        if (request('school_id')) {
            $paymentsQuery->where('school_id', request('school_id'));
        }
        if (request('term')) {
            $paymentsQuery->where('term', request('term'));
        }

        $paymentsPerSchool = $paymentsQuery->get()->keyBy('school_id');

        $schoolsQuery = School::orderBy('school_name')->get(['id', 'school_name']);
        if (request('school_id')) {
            $schoolsQuery = $schoolsQuery->where('id', (int) request('school_id'));
        }

        $payments = $schoolsQuery
            ->map(function ($school) use ($paymentsPerSchool) {
                $payment = $paymentsPerSchool[$school->id] ?? null;
                $due = $payment->total_due ?? 0;
                $paid = $payment->total_paid ?? 0;
                $outstanding = max(0, $due - $paid);
                return [
                    'school' => $school->school_name,
                    'total_due' => $due,
                    'total_paid' => $paid,
                    'outstanding' => $outstanding,
                ];
            });

        $workloadQuery = User::query()
            ->where('role', 'instructor')
            ->withCount('teachingClasses')
            ->orderBy('name');

        if (request('school_id')) {
            $schoolId = (int) request('school_id');
            $workloadQuery->whereHas('teachingClasses', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            });
        }

        $workload = $workloadQuery->get(['id', 'name', 'email'])
            ->map(fn ($instructor) => [
                'instructor' => $instructor->name,
                'classes' => $instructor->teaching_classes_count,
            ]);

        $states = School::orderBy('state')
            ->when(request('school_id'), fn ($q) => $q->where('id', request('school_id')))
            ->get(['state'])
            ->groupBy('state')
            ->map(fn ($items, $state) => [
                'state' => $state,
                'count' => $items->count(),
            ])
            ->values();

        return response()->streamDownload(function () use ($students, $payments, $workload, $states) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['STUDENTS PER SCHOOL']);
            fputcsv($out, ['school', 'students']);
            foreach ($students as $row) {
                fputcsv($out, $row);
            }

            fputcsv($out, []);
            fputcsv($out, ['PAYMENTS PER SCHOOL']);
            fputcsv($out, ['school', 'total_due', 'total_paid', 'outstanding']);
            foreach ($payments as $row) {
                fputcsv($out, $row);
            }

            fputcsv($out, []);
            fputcsv($out, ['INSTRUCTOR WORKLOAD']);
            fputcsv($out, ['instructor', 'classes']);
            foreach ($workload as $row) {
                fputcsv($out, $row);
            }

            fputcsv($out, []);
            fputcsv($out, ['SCHOOLS BY STATE']);
            fputcsv($out, ['state', 'count']);
            foreach ($states as $row) {
                fputcsv($out, $row);
            }

            fclose($out);
        }, 'reports_all.csv');
    }

    private function csv(string $filename, array $headers, $rows)
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename);
    }
}
