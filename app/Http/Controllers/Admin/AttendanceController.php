<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\School;
use App\Models\User;

class AttendanceController extends Controller
{
    public function index()
    {
        $query = $this->buildQuery();
        $records = (clone $query)->limit(200)->get();

        $summaryQuery = $this->buildQuery(false);
        $totalRecords = (clone $summaryQuery)->count();
        $presentCount = (clone $summaryQuery)->where('status', 'present')->count();
        $absentCount = (clone $summaryQuery)->where('status', 'absent')->count();
        $attendanceRate = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 1) : 0;
        $bySchool = (clone $summaryQuery)
            ->selectRaw('school_id, COUNT(*) as total, SUM(status = "present") as present_count')
            ->groupBy('school_id')
            ->with('classroom.school')
            ->get();

        $byClass = (clone $summaryQuery)
            ->selectRaw('class_id, COUNT(*) as total, SUM(status = "present") as present_count')
            ->groupBy('class_id')
            ->with('classroom')
            ->get();

        $byInstructor = (clone $summaryQuery)
            ->selectRaw('marked_by, COUNT(*) as total, SUM(status = "present") as present_count')
            ->groupBy('marked_by')
            ->get()
            ->keyBy('marked_by');

        $instructorMap = User::where('role', 'instructor')
            ->orderBy('name')
            ->get(['id', 'name', 'email'])
            ->keyBy('id');
        $schools = School::orderBy('school_name')->get(['id', 'school_name']);
        $classes = Classroom::orderBy('name')->get(['id', 'name', 'school_id']);
        $instructors = User::where('role', 'instructor')->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.attendance.index', compact(
            'records',
            'schools',
            'classes',
            'instructors',
            'totalRecords',
            'presentCount',
            'absentCount',
            'attendanceRate',
            'bySchool',
            'byClass',
            'byInstructor',
            'instructorMap'
        ));
    }

    public function export()
    {
        $query = $this->buildQuery();
        $rows = $query->get()->map(function ($row) {
            return [
                'date' => $row->date,
                'school' => $row->classroom->school->school_name ?? 'N/A',
                'class' => $row->classroom->name ?? 'N/A',
                'student' => trim(($row->student->first_name ?? '') . ' ' . ($row->student->last_name ?? '')),
                'status' => $row->status,
                'marked_by' => $row->marker->name ?? 'N/A',
            ];
        });

        $headers = array_keys($rows->first() ?? [
            'date' => '',
            'school' => '',
            'class' => '',
            'student' => '',
            'status' => '',
            'marked_by' => '',
        ]);

        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, 'attendance.csv');
    }

    public function exportSummary()
    {
        $group = request('group', 'school');
        $summaryQuery = $this->buildQuery(false);

        if ($group === 'class') {
            $rows = (clone $summaryQuery)
                ->selectRaw('class_id, COUNT(*) as total, SUM(status = "present") as present_count')
                ->groupBy('class_id')
                ->get()
                ->map(function ($row) {
                    $class = Classroom::find($row->class_id);
                    $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                    return [
                        'class' => $class->name ?? 'N/A',
                        'present' => $row->present_count,
                        'total' => $row->total,
                        'rate' => $rate . '%',
                    ];
                });
            $filename = 'attendance_summary_by_class.csv';
        } elseif ($group === 'instructor') {
            $rows = (clone $summaryQuery)
                ->selectRaw('marked_by, COUNT(*) as total, SUM(status = "present") as present_count')
                ->groupBy('marked_by')
                ->get()
                ->map(function ($row) {
                    $inst = User::find($row->marked_by);
                    $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                    return [
                        'instructor' => $inst->name ?? 'N/A',
                        'present' => $row->present_count,
                        'total' => $row->total,
                        'rate' => $rate . '%',
                    ];
                });
            $filename = 'attendance_summary_by_instructor.csv';
        } else {
            $rows = (clone $summaryQuery)
                ->selectRaw('school_id, COUNT(*) as total, SUM(status = "present") as present_count')
                ->groupBy('school_id')
                ->get()
                ->map(function ($row) {
                    $school = School::find($row->school_id);
                    $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                    return [
                        'school' => $school->school_name ?? 'N/A',
                        'present' => $row->present_count,
                        'total' => $row->total,
                        'rate' => $rate . '%',
                    ];
                });
            $filename = 'attendance_summary_by_school.csv';
        }

        $headers = array_keys($rows->first() ?? [
            'name' => '',
            'present' => '',
            'total' => '',
            'rate' => '',
        ]);

        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename);
    }

    public function exportAllSummaries()
    {
        $summaryQuery = $this->buildQuery(false);

        $bySchool = (clone $summaryQuery)
            ->selectRaw('school_id, COUNT(*) as total, SUM(status = "present") as present_count')
            ->groupBy('school_id')
            ->get()
            ->map(function ($row) {
                $school = School::find($row->school_id);
                $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                return [
                    'school' => $school->school_name ?? 'N/A',
                    'present' => $row->present_count,
                    'total' => $row->total,
                    'rate' => $rate . '%',
                ];
            });

        $byClass = (clone $summaryQuery)
            ->selectRaw('class_id, COUNT(*) as total, SUM(status = "present") as present_count')
            ->groupBy('class_id')
            ->get()
            ->map(function ($row) {
                $class = Classroom::find($row->class_id);
                $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                return [
                    'class' => $class->name ?? 'N/A',
                    'present' => $row->present_count,
                    'total' => $row->total,
                    'rate' => $rate . '%',
                ];
            });

        $byInstructor = (clone $summaryQuery)
            ->selectRaw('marked_by, COUNT(*) as total, SUM(status = "present") as present_count')
            ->groupBy('marked_by')
            ->get()
            ->map(function ($row) {
                $inst = User::find($row->marked_by);
                $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                return [
                    'instructor' => $inst->name ?? 'N/A',
                    'present' => $row->present_count,
                    'total' => $row->total,
                    'rate' => $rate . '%',
                ];
            });

        return response()->streamDownload(function () use ($bySchool, $byClass, $byInstructor) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['SUMMARY BY SCHOOL']);
            fputcsv($out, ['school', 'present', 'total', 'rate']);
            foreach ($bySchool as $row) {
                fputcsv($out, $row);
            }

            fputcsv($out, []);
            fputcsv($out, ['SUMMARY BY CLASS']);
            fputcsv($out, ['class', 'present', 'total', 'rate']);
            foreach ($byClass as $row) {
                fputcsv($out, $row);
            }

            fputcsv($out, []);
            fputcsv($out, ['SUMMARY BY INSTRUCTOR']);
            fputcsv($out, ['instructor', 'present', 'total', 'rate']);
            foreach ($byInstructor as $row) {
                fputcsv($out, $row);
            }

            fclose($out);
        }, 'attendance_summary_all.csv');
    }

    private function buildQuery(bool $withRelations = true)
    {
        $query = Attendance::query()
            ->orderBy('date', 'desc');

        if ($withRelations) {
            $query->with(['student', 'classroom.school', 'marker']);
        }

        if (request('school_id')) {
            $query->where('school_id', request('school_id'));
        }

        if (request('class_id')) {
            $query->where('class_id', request('class_id'));
        }

        if (request('instructor_id')) {
            $query->where('marked_by', request('instructor_id'));
        }

        if (request('from')) {
            $query->whereDate('date', '>=', request('from'));
        }

        if (request('to')) {
            $query->whereDate('date', '<=', request('to'));
        }

        return $query;
    }
}
