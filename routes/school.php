<?php

use App\Http\Controllers\School\DashboardController;
use App\Http\Controllers\School\StudentController;
use App\Http\Controllers\School\AttendanceController;
use App\Http\Controllers\School\ClassTeacherController;
use App\Http\Controllers\School\ClassTeacherFeedbackController;
use App\Http\Controllers\School\ExamAssignmentController;
use Illuminate\Support\Facades\Route;

Route::domain('school.genchess.ng')
    ->middleware(['auth', 'verified', 'schooladmin'])
    ->group(function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });

Route::middleware(['auth', 'verified', 'schooladmin'])->prefix('school')->name('school.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\School\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\School\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\School\ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/classes', [\App\Http\Controllers\School\ClassController::class, 'index'])
        ->name('classes.index');

    Route::get('/classes/create', [\App\Http\Controllers\School\ClassController::class, 'create'])
        ->name('classes.create');

    Route::post('/classes', [\App\Http\Controllers\School\ClassController::class, 'store'])
        ->name('classes.store');
    Route::get('/classes/{classroom}/edit', [\App\Http\Controllers\School\ClassController::class, 'edit'])
        ->name('classes.edit');
    Route::patch('/classes/{classroom}', [\App\Http\Controllers\School\ClassController::class, 'update'])
        ->name('classes.update');

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/bulk-upload', [StudentController::class, 'bulkUploadForm'])->name('students.bulk.form');
    Route::post('/students/bulk-upload', [StudentController::class, 'bulkUploadStore'])->name('students.bulk.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::patch('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/class-teachers', [ClassTeacherController::class, 'index'])->name('class-teachers.index');
    Route::get('/class-teachers/create', [ClassTeacherController::class, 'create'])->name('class-teachers.create');
    Route::post('/class-teachers', [ClassTeacherController::class, 'store'])->name('class-teachers.store');
    Route::get('/class-teachers/{classTeacher}/edit', [ClassTeacherController::class, 'edit'])->name('class-teachers.edit');
    Route::patch('/class-teachers/{classTeacher}', [ClassTeacherController::class, 'update'])->name('class-teachers.update');
    Route::patch('/class-teachers/{classTeacher}/status', [ClassTeacherController::class, 'updateStatus'])
        ->name('class-teachers.status');

    Route::get('/class-teacher-feedback', [ClassTeacherFeedbackController::class, 'index'])
        ->name('class-teacher-feedback.index');
    Route::get('/class-teacher-feedback/create', [ClassTeacherFeedbackController::class, 'create'])
        ->name('class-teacher-feedback.create');
    Route::post('/class-teacher-feedback', [ClassTeacherFeedbackController::class, 'store'])
        ->name('class-teacher-feedback.store');

    Route::post('/attendance', [AttendanceController::class, 'store'])
        ->name('attendance.store');

    Route::get('/attendance/{class}', [AttendanceController::class, 'index'])
        ->whereNumber('class')
        ->name('attendance.index');

    Route::get('/attendance/{class}/create', [AttendanceController::class, 'create'])
        ->whereNumber('class')
        ->name('attendance.create');

    Route::get('/attendance/report', [\App\Http\Controllers\School\AttendanceReportController::class, 'index'])
        ->name('attendance.report');

    Route::get('/attendance/report/{classroom}', [\App\Http\Controllers\School\AttendanceReportController::class, 'show'])
        ->name('attendance.show');

    Route::get(
        '/attendance/summary/{classroom}',
        [\App\Http\Controllers\School\AttendanceReportController::class, 'summary']
    )->name('attendance.summary');

    Route::get('/finance', [\App\Http\Controllers\School\FinanceController::class, 'index'])
        ->name('finance.index');
    Route::post('/finance/{payment}/pay', [\App\Http\Controllers\School\FinanceController::class, 'pay'])
        ->name('finance.pay');
    Route::get('/finance/{payment}/invoice', [\App\Http\Controllers\School\FinanceController::class, 'invoice'])
        ->name('finance.invoice');

    Route::get('/instructors', [\App\Http\Controllers\School\InstructorController::class, 'index'])
        ->name('instructors.index');

    Route::get('/timetables', [\App\Http\Controllers\School\TimetableController::class, 'index'])
        ->name('timetables.index');
    Route::get('/timetables/create', [\App\Http\Controllers\School\TimetableController::class, 'create'])
        ->name('timetables.create');
    Route::post('/timetables', [\App\Http\Controllers\School\TimetableController::class, 'store'])
        ->name('timetables.store');
    Route::get('/timetables/{timetable}/edit', [\App\Http\Controllers\School\TimetableController::class, 'edit'])
        ->name('timetables.edit');
    Route::patch('/timetables/{timetable}', [\App\Http\Controllers\School\TimetableController::class, 'update'])
        ->name('timetables.update');
    Route::post('/timetables/{timetable}/submit', [\App\Http\Controllers\School\TimetableController::class, 'submit'])
        ->name('timetables.submit');

    Route::get('/exams', [\App\Http\Controllers\School\ExamController::class, 'index'])
        ->name('exams.index');
    Route::get('/exams/{exam}', [\App\Http\Controllers\School\ExamController::class, 'show'])
        ->name('exams.show');
    Route::delete('/exams/{exam}', [\App\Http\Controllers\School\ExamController::class, 'destroy'])
        ->name('exams.destroy');

    Route::get('/exam-assignments', [ExamAssignmentController::class, 'index'])
        ->name('exams.assignments.index');
    Route::get('/exam-assignments/create', [ExamAssignmentController::class, 'create'])
        ->name('exams.assignments.create');
    Route::post('/exam-assignments', [ExamAssignmentController::class, 'store'])
        ->name('exams.assignments.store');
    Route::delete('/exam-assignments/bulk-delete', [ExamAssignmentController::class, 'bulkDestroy'])
        ->name('exams.assignments.bulk-destroy');
    Route::get('/exam-assignments/{assignment}/print', [ExamAssignmentController::class, 'print'])
        ->name('exams.assignments.print');
    Route::get('/exam-assignments/{assignment}/results', [ExamAssignmentController::class, 'results'])
        ->name('exams.assignments.results');
    Route::patch('/exam-assignments/{assignment}/status', [ExamAssignmentController::class, 'updateStatus'])
        ->name('exams.assignments.status');
    Route::delete('/exam-assignments/{assignment}', [ExamAssignmentController::class, 'destroy'])
        ->name('exams.assignments.destroy');
    Route::get('/results', [\App\Http\Controllers\School\StudentResultController::class, 'index'])
        ->name('results.index');
    Route::get('/results-export', [\App\Http\Controllers\School\StudentResultController::class, 'export'])
        ->name('results.export');
    Route::get('/results-summary-pdf', [\App\Http\Controllers\School\StudentResultController::class, 'summaryPdf'])
        ->name('results.summary-pdf');
    Route::get('/results/{result}', [\App\Http\Controllers\School\StudentResultController::class, 'show'])
        ->name('results.show');
    Route::get('/results/{result}/print', [\App\Http\Controllers\School\StudentResultController::class, 'print'])
        ->name('results.print');
});
