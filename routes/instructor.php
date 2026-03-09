<?php

use App\Http\Controllers\Instructor\DashboardController;
use Illuminate\Support\Facades\Route;

Route::domain('instructor.genchess.ng')
    ->middleware(['auth', 'verified', 'instructor'])
    ->group(function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    });

Route::middleware(['auth', 'verified', 'instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Instructor\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/classes', [\App\Http\Controllers\Instructor\ClassOverviewController::class, 'index'])
        ->name('classes.index');

    Route::get('/assignments', [\App\Http\Controllers\Instructor\AssignmentController::class, 'index'])
        ->name('assignments.index');
    Route::delete('/assignments/{classroom}', [\App\Http\Controllers\Instructor\AssignmentController::class, 'destroy'])
        ->name('assignments.destroy');

    Route::get('/attendance', [\App\Http\Controllers\Instructor\AttendanceController::class, 'selectClass'])
        ->name('attendance.select');
    Route::get('/classes/{classroom}/attendance', [\App\Http\Controllers\Instructor\AttendanceController::class, 'index'])
        ->name('attendance.index');
    Route::post('/classes/{classroom}/attendance', [\App\Http\Controllers\Instructor\AttendanceController::class, 'store'])
        ->name('attendance.store');

    Route::get('/training', [\App\Http\Controllers\Instructor\TrainingController::class, 'index'])
        ->name('training.index');
    Route::get('/training/{enrollment}', [\App\Http\Controllers\Instructor\TrainingProgressController::class, 'show'])
        ->middleware('training.paid')
        ->name('training.show');
    Route::get('/training/{enrollment}/topics/{topic}/quiz', [\App\Http\Controllers\Instructor\TrainingProgressController::class, 'showQuiz'])
        ->middleware('training.paid')
        ->name('training.topics.quiz.show');
    Route::post('/training/{enrollment}/topics/{topic}/quiz', [\App\Http\Controllers\Instructor\TrainingProgressController::class, 'submitQuiz'])
        ->middleware('training.paid')
        ->name('training.topics.quiz.submit');
    Route::post('/training/{enrollment}/topics/{topic}', [\App\Http\Controllers\Instructor\TrainingProgressController::class, 'submitTopic'])
        ->middleware('training.paid')
        ->name('training.topics.submit');
    Route::post('/training/{enrollment}/capstone', [\App\Http\Controllers\Instructor\TrainingProgressController::class, 'submitCapstone'])
        ->middleware('training.paid')
        ->name('training.capstone.submit');
    Route::post('/training/{enrollment}/discussions', [\App\Http\Controllers\Instructor\TrainingProgressController::class, 'postDiscussion'])
        ->middleware('training.paid')
        ->name('training.discussions.store');
    Route::post('/training/{enrollment}/teaching-practice', [\App\Http\Controllers\Instructor\TrainingProgressController::class, 'submitTeachingPractice'])
        ->middleware('training.paid')
        ->name('training.teaching-practice.store');

    Route::get('/lesson-plans', [\App\Http\Controllers\Instructor\LessonPlanController::class, 'index'])
        ->name('lesson-plans.index');
    Route::get('/lesson-plans/create', [\App\Http\Controllers\Instructor\LessonPlanController::class, 'create'])
        ->name('lesson-plans.create');
    Route::post('/lesson-plans', [\App\Http\Controllers\Instructor\LessonPlanController::class, 'store'])
        ->name('lesson-plans.store');
    Route::get('/lesson-plans/{lessonPlan}/edit', [\App\Http\Controllers\Instructor\LessonPlanController::class, 'edit'])
        ->name('lesson-plans.edit');
    Route::patch('/lesson-plans/{lessonPlan}', [\App\Http\Controllers\Instructor\LessonPlanController::class, 'update'])
        ->name('lesson-plans.update');
    Route::post('/lesson-plans/{lessonPlan}/submit', [\App\Http\Controllers\Instructor\LessonPlanController::class, 'submit'])
        ->name('lesson-plans.submit');
    Route::delete('/lesson-plans/{lessonPlan}', [\App\Http\Controllers\Instructor\LessonPlanController::class, 'destroy'])
        ->name('lesson-plans.destroy');

    Route::get('/timetable', [\App\Http\Controllers\Instructor\TimetableController::class, 'index'])
        ->name('timetable.index');
    Route::get('/timetable/create', [\App\Http\Controllers\Instructor\TimetableController::class, 'create'])
        ->name('timetable.create');
    Route::post('/timetable', [\App\Http\Controllers\Instructor\TimetableController::class, 'store'])
        ->name('timetable.store');
    Route::get('/timetable/{timetable}/edit', [\App\Http\Controllers\Instructor\TimetableController::class, 'edit'])
        ->name('timetable.edit');
    Route::patch('/timetable/{timetable}', [\App\Http\Controllers\Instructor\TimetableController::class, 'update'])
        ->name('timetable.update');
    Route::post('/school-timetables/{timetable}/respond', [\App\Http\Controllers\Instructor\TimetableController::class, 'respondToSchoolTimetable'])
        ->name('school-timetables.respond');
    Route::delete('/timetable/{timetable}', [\App\Http\Controllers\Instructor\TimetableController::class, 'destroy'])
        ->name('timetable.destroy');

    Route::get('/scheme-of-work', [\App\Http\Controllers\Instructor\SchemeOfWorkController::class, 'index'])
        ->name('scheme.index');

    Route::get('/exam-assignments', [\App\Http\Controllers\Instructor\ExamAssignmentController::class, 'index'])
        ->name('exams.assignments.index');
    Route::get('/exam-assignments/{assignment}/grade', [\App\Http\Controllers\Instructor\ExamAssignmentController::class, 'grade'])
        ->name('exams.assignments.grade');
    Route::post('/exam-assignments/{assignment}/grade', [\App\Http\Controllers\Instructor\ExamAssignmentController::class, 'storeGrades'])
        ->name('exams.assignments.grade.store');
    Route::get('/results', [\App\Http\Controllers\Instructor\StudentResultController::class, 'index'])
        ->name('results.index');
    Route::get('/results/create', [\App\Http\Controllers\Instructor\StudentResultController::class, 'create'])
        ->name('results.create');
    Route::post('/results', [\App\Http\Controllers\Instructor\StudentResultController::class, 'store'])
        ->name('results.store');
    Route::get('/results/{result}/edit', [\App\Http\Controllers\Instructor\StudentResultController::class, 'edit'])
        ->name('results.edit');
    Route::patch('/results/{result}', [\App\Http\Controllers\Instructor\StudentResultController::class, 'update'])
        ->name('results.update');
});
