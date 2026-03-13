<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SchoolApprovalController;
use App\Http\Controllers\Admin\SchoolRequestAdminController;
use App\Http\Controllers\Admin\ClassTeacherFeedbackController as AdminClassTeacherFeedbackController;
use App\Http\Controllers\Admin\InstructorAssignmentController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\ExamTemplateController;
use App\Http\Controllers\Admin\SchemeOfWorkController as AdminSchemeOfWorkController;
use App\Http\Controllers\Admin\ClassController as AdminClassController;
use App\Http\Controllers\Admin\InstructorController as AdminInstructorController;
use App\Http\Controllers\Admin\TimetableReviewController as AdminTimetableReviewController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use Illuminate\Support\Facades\Route;

Route::domain('admin.genchess.ng')
    ->middleware(['auth', 'verified', 'superadmin'])
    ->group(function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });

Route::middleware(['auth', 'verified', 'superadmin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/schools', [SchoolApprovalController::class, 'index'])->name('admin.schools.index');
        Route::get('/schools/create', [SchoolApprovalController::class, 'create'])->name('admin.schools.create');
        Route::post('/schools', [SchoolApprovalController::class, 'store'])->name('admin.schools.store');
        Route::post('/schools/{id}/approve', [SchoolApprovalController::class, 'approve'])->name('admin.schools.approve');
        Route::patch('/schools/{id}/status', [SchoolApprovalController::class, 'updateStatus'])
            ->name('admin.schools.status');

        Route::get('/finance', [\App\Http\Controllers\Admin\FinanceController::class, 'index'])
            ->name('admin.finance.index');
        Route::post('/finance/pricing', [\App\Http\Controllers\Admin\FinanceController::class, 'storePricing'])
            ->name('admin.finance.pricing.store');
        Route::post('/finance/payments', [\App\Http\Controllers\Admin\FinanceController::class, 'storePayment'])
            ->name('admin.finance.payments.store');
        Route::post('/finance/generate', [\App\Http\Controllers\Admin\FinanceController::class, 'generatePayment'])
            ->name('admin.finance.generate');
        Route::post('/finance/generate-bulk', [\App\Http\Controllers\Admin\FinanceController::class, 'generatePaymentsBulk'])
            ->name('admin.finance.generate.bulk');
        Route::post('/finance/generate-bulk-preview', [\App\Http\Controllers\Admin\FinanceController::class, 'previewGeneratePaymentsBulk'])
            ->name('admin.finance.generate.bulk.preview');
        Route::get('/finance/export', [\App\Http\Controllers\Admin\FinanceController::class, 'exportPayments'])
            ->name('admin.finance.export');

        Route::get('/training', [\App\Http\Controllers\Admin\TrainingController::class, 'index'])
            ->name('admin.training.index');
        Route::post('/training/courses', [\App\Http\Controllers\Admin\TrainingController::class, 'storeCourse'])
            ->name('admin.training.courses.store');
        Route::get('/training/courses/{course}/curriculum', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'showCourse'])
            ->name('admin.training.courses.curriculum');
        Route::post('/training/courses/{course}/modules', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'storeModule'])
            ->name('admin.training.modules.store');
        Route::patch('/training/modules/{module}', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'updateModule'])
            ->name('admin.training.modules.update');
        Route::delete('/training/modules/{module}', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'destroyModule'])
            ->name('admin.training.modules.destroy');
        Route::post('/training/modules/{module}/topics', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'storeTopic'])
            ->name('admin.training.topics.store');
        Route::patch('/training/topics/{topic}', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'updateTopic'])
            ->name('admin.training.topics.update');
        Route::delete('/training/topics/{topic}', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'destroyTopic'])
            ->name('admin.training.topics.destroy');
        Route::post('/training/topics/{topic}/quiz', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'upsertQuiz'])
            ->name('admin.training.quizzes.upsert');
        Route::post('/training/quizzes/{quiz}/questions', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'storeQuizQuestion'])
            ->name('admin.training.quiz-questions.store');
        Route::patch('/training/quiz-questions/{question}', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'updateQuizQuestion'])
            ->name('admin.training.quiz-questions.update');
        Route::delete('/training/quiz-questions/{question}', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'destroyQuizQuestion'])
            ->name('admin.training.quiz-questions.destroy');
        Route::post('/training/topics/{topic}/assignments', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'storeAssignment'])
            ->name('admin.training.assignments.store');
        Route::patch('/training/assignments/{assignment}', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'updateAssignment'])
            ->name('admin.training.assignments.update');
        Route::delete('/training/assignments/{assignment}', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'destroyAssignment'])
            ->name('admin.training.assignments.destroy');
        Route::patch('/training/submissions/{submission}/review', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'reviewSubmission'])
            ->name('admin.training.submissions.review');
        Route::patch('/training/capstone/{capstoneReview}/review', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'reviewCapstone'])
            ->name('admin.training.capstone.review');
        Route::post('/training/courses/{course}/live-classes', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'storeLiveClass'])
            ->name('admin.training.live-classes.store');
        Route::delete('/training/live-classes/{liveClass}', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'destroyLiveClass'])
            ->name('admin.training.live-classes.destroy');
        Route::patch('/training/teaching-practice/{practice}/review', [\App\Http\Controllers\Admin\TrainingCurriculumController::class, 'reviewTeachingPractice'])
            ->name('admin.training.teaching-practice.review');
        Route::post('/training/cohorts', [\App\Http\Controllers\Admin\TrainingController::class, 'storeCohort'])
            ->name('admin.training.cohorts.store');
        Route::get('/training/cohorts/{cohort}', [\App\Http\Controllers\Admin\TrainingController::class, 'showCohort'])
            ->name('admin.training.cohorts.show');
        Route::post('/training/cohorts/{cohort}/enroll', [\App\Http\Controllers\Admin\TrainingController::class, 'enroll'])
            ->name('admin.training.enroll');
        Route::patch('/training/enrollments/{enrollment}', [\App\Http\Controllers\Admin\TrainingController::class, 'updateEnrollment'])
            ->name('admin.training.enrollments.update');
        Route::post('/training/enrollments/{enrollment}/certificate', [\App\Http\Controllers\Admin\TrainingController::class, 'issueCertificate'])
            ->name('admin.training.enrollments.certificate');
        Route::post('/training/coupons', [\App\Http\Controllers\Admin\TrainingController::class, 'storeCoupon'])
            ->name('admin.training.coupons.store');
        Route::patch('/training/coupons/{coupon}', [\App\Http\Controllers\Admin\TrainingController::class, 'updateCoupon'])
            ->name('admin.training.coupons.update');
        Route::post('/training/coupons/manual-assign', [\App\Http\Controllers\Admin\TrainingController::class, 'assignManualCoupon'])
            ->name('admin.training.coupons.assign');
        Route::get('/training/certificates/{certification}', [\App\Http\Controllers\Admin\CertificateController::class, 'show'])
            ->name('admin.training.certificates.show');
        Route::get('/training/certificates/{certification}/download', [\App\Http\Controllers\Admin\CertificateController::class, 'download'])
            ->name('admin.training.certificates.download');

        Route::get('/store/categories', [\App\Http\Controllers\Admin\StoreCategoryController::class, 'index'])
            ->name('admin.store.categories.index');
        Route::post('/store/categories', [\App\Http\Controllers\Admin\StoreCategoryController::class, 'store'])
            ->name('admin.store.categories.store');
        Route::patch('/store/categories/{category}', [\App\Http\Controllers\Admin\StoreCategoryController::class, 'update'])
            ->name('admin.store.categories.update');

        Route::get('/store/products', [\App\Http\Controllers\Admin\StoreProductController::class, 'index'])
            ->name('admin.store.products.index');
        Route::post('/store/products', [\App\Http\Controllers\Admin\StoreProductController::class, 'store'])
            ->name('admin.store.products.store');
        Route::patch('/store/products/{product}', [\App\Http\Controllers\Admin\StoreProductController::class, 'update'])
            ->name('admin.store.products.update');
        Route::patch('/store/products/{product}/featured', [\App\Http\Controllers\Admin\StoreProductController::class, 'toggleFeatured'])
            ->name('admin.store.products.featured');
        Route::get('/store/products/{product}/images', [\App\Http\Controllers\Admin\StoreProductController::class, 'images'])
            ->name('admin.store.products.images');
        Route::post('/store/products/{product}/images', [\App\Http\Controllers\Admin\StoreProductController::class, 'storeImage'])
            ->name('admin.store.products.images.store');
        Route::patch('/store/products/{product}/images/{image}/primary', [\App\Http\Controllers\Admin\StoreProductController::class, 'setPrimaryImage'])
            ->name('admin.store.products.images.primary');
        Route::delete('/store/products/{product}/images/{image}', [\App\Http\Controllers\Admin\StoreProductController::class, 'destroyImage'])
            ->name('admin.store.products.images.destroy');

        Route::get('/store/orders', [\App\Http\Controllers\Admin\StoreOrderController::class, 'index'])
            ->name('admin.store.orders.index');
        Route::get('/store/orders/{order}/invoice', [\App\Http\Controllers\Admin\StoreOrderController::class, 'invoice'])
            ->name('admin.store.orders.invoice');
        Route::patch('/store/orders/{order}/status', [\App\Http\Controllers\Admin\StoreOrderController::class, 'updateStatus'])
            ->name('admin.store.orders.update-status');
        Route::get('/store/inventory', [\App\Http\Controllers\Admin\StoreOrderController::class, 'inventory'])
            ->name('admin.store.inventory.index');

        Route::get('/store/bulk-orders', [\App\Http\Controllers\Admin\StoreBulkOrderController::class, 'index'])
            ->name('admin.store.bulk-orders.index');
        Route::patch('/store/bulk-orders/{bulkOrder}', [\App\Http\Controllers\Admin\StoreBulkOrderController::class, 'update'])
            ->name('admin.store.bulk-orders.update');

        Route::get('/careers', [\App\Http\Controllers\Admin\CareerController::class, 'index'])
            ->name('admin.careers.index');
        Route::get('/instructor-screenings', [\App\Http\Controllers\Admin\InstructorScreeningController::class, 'index'])
            ->name('admin.instructor-screenings.index');
        Route::get('/instructor-screenings/export', [\App\Http\Controllers\Admin\InstructorScreeningController::class, 'export'])
            ->name('admin.instructor-screenings.export');
        Route::get('/instructor-screenings/{screening}', [\App\Http\Controllers\Admin\InstructorScreeningController::class, 'show'])
            ->name('admin.instructor-screenings.show');
        Route::patch('/instructor-screenings/{screening}/workflow', [\App\Http\Controllers\Admin\InstructorScreeningController::class, 'updateWorkflow'])
            ->name('admin.instructor-screenings.workflow.update');
        Route::patch('/enrollments/{schoolRequest}/consultation', [SchoolRequestAdminController::class, 'scheduleConsultation'])
            ->name('admin.enrollments.consultation');
        Route::post('/careers/jobs', [\App\Http\Controllers\Admin\CareerController::class, 'storeJob'])
            ->name('admin.careers.jobs.store');
        Route::patch('/careers/applications/{application}', [\App\Http\Controllers\Admin\CareerController::class, 'updateApplication'])
            ->name('admin.careers.applications.update');
        Route::get('/careers/applications/{application}/cv', [\App\Http\Controllers\Admin\CareerController::class, 'downloadCv'])
            ->name('admin.careers.applications.cv');

        Route::get('/class-teacher-feedback', [AdminClassTeacherFeedbackController::class, 'index'])
            ->name('admin.class-teacher-feedback.index');

        Route::get('/attendance', [AdminAttendanceController::class, 'index'])
            ->name('admin.attendance.index');
        Route::get('/attendance/export', [AdminAttendanceController::class, 'export'])
            ->name('admin.attendance.export');
        Route::get('/attendance/export-summary', [AdminAttendanceController::class, 'exportSummary'])
            ->name('admin.attendance.export-summary');
        Route::get('/attendance/export-summary-all', [AdminAttendanceController::class, 'exportAllSummaries'])
            ->name('admin.attendance.export-summary-all');

        Route::get('/reports', [AdminReportsController::class, 'index'])
            ->name('admin.reports.index');
        Route::get('/reports/export/students', [AdminReportsController::class, 'exportStudents'])
            ->name('admin.reports.export.students');
        Route::get('/reports/export/payments', [AdminReportsController::class, 'exportPayments'])
            ->name('admin.reports.export.payments');
        Route::get('/reports/export/workload', [AdminReportsController::class, 'exportWorkload'])
            ->name('admin.reports.export.workload');
        Route::get('/reports/export/states', [AdminReportsController::class, 'exportStates'])
            ->name('admin.reports.export.states');
        Route::get('/reports/export/all', [AdminReportsController::class, 'exportAll'])
            ->name('admin.reports.export.all');

        Route::get('/lesson-plans', [\App\Http\Controllers\Admin\LessonPlanReviewController::class, 'index'])
            ->name('admin.lesson-plans.index');
        Route::patch('/lesson-plans/{lessonPlan}/review', [\App\Http\Controllers\Admin\LessonPlanReviewController::class, 'review'])
            ->name('admin.lesson-plans.review');

        Route::get('/settings', [AdminSettingsController::class, 'index'])
            ->name('admin.settings.index');
        Route::post('/settings', [AdminSettingsController::class, 'update'])
            ->name('admin.settings.update');

        Route::get('/instructor-assignments', [InstructorAssignmentController::class, 'index'])
            ->name('admin.instructor-assignments.index');
        Route::post('/instructor-assignments', [InstructorAssignmentController::class, 'store'])
            ->name('admin.instructor-assignments.store');
        Route::delete(
            '/instructor-assignments/{classroom}/instructors/{instructor}',
            [InstructorAssignmentController::class, 'destroy']
        )->name('admin.instructor-assignments.destroy');

        Route::get('/instructors', [AdminInstructorController::class, 'index'])
            ->name('admin.instructors.index');
        Route::post('/instructors', [AdminInstructorController::class, 'store'])
            ->name('admin.instructors.store');
        Route::get('/instructors/{instructor}', [AdminInstructorController::class, 'show'])
            ->name('admin.instructors.show');
        Route::patch('/instructors/{instructor}/status', [AdminInstructorController::class, 'updateStatus'])
            ->name('admin.instructors.status');
        Route::post('/instructors/{instructor}/reset-link', [AdminInstructorController::class, 'sendResetLink'])
            ->name('admin.instructors.reset-link');

        Route::get('/students', [AdminStudentController::class, 'index'])
            ->name('admin.students.index');
        Route::post('/students/{student}/approve', [AdminStudentController::class, 'approve'])
            ->name('admin.students.approve');
        Route::post('/students/{student}/reject', [AdminStudentController::class, 'reject'])
            ->name('admin.students.reject');
        Route::patch('/students/{student}/move', [AdminStudentController::class, 'move'])
            ->name('admin.students.move');

        Route::get('/classes', [AdminClassController::class, 'index'])
            ->name('admin.classes.index');
        Route::patch('/classes/{classroom}/status', [AdminClassController::class, 'updateStatus'])
            ->name('admin.classes.status');

        Route::get('/exam-templates', [ExamTemplateController::class, 'index'])
            ->name('admin.exams.templates.index');
        Route::get('/exam-templates/create', [ExamTemplateController::class, 'create'])
            ->name('admin.exams.templates.create');
        Route::post('/exam-templates', [ExamTemplateController::class, 'store'])
            ->name('admin.exams.templates.store');
        Route::get('/exam-templates/{template}/edit', [ExamTemplateController::class, 'edit'])
            ->name('admin.exams.templates.edit');
        Route::patch('/exam-templates/{template}', [ExamTemplateController::class, 'update'])
            ->name('admin.exams.templates.update');
        Route::delete('/exam-templates/{template}', [ExamTemplateController::class, 'destroy'])
            ->name('admin.exams.templates.destroy');
        Route::get('/exam-templates/{template}', [ExamTemplateController::class, 'show'])
            ->name('admin.exams.templates.show');
        Route::post('/exam-templates/{template}/questions', [ExamTemplateController::class, 'storeQuestion'])
            ->name('admin.exams.templates.questions.store');
        Route::get('/exam-templates/{template}/export', [ExamTemplateController::class, 'exportTemplate'])
            ->name('admin.exams.templates.export');
        Route::post('/exam-templates/{template}/import', [ExamTemplateController::class, 'importQuestions'])
            ->name('admin.exams.templates.import');
        Route::get('/exam-templates-import-template', [ExamTemplateController::class, 'downloadImportTemplate'])
            ->name('admin.exams.templates.import.template');
        Route::get('/exam-questions', [\App\Http\Controllers\Admin\ExamQuestionManagementController::class, 'index'])
            ->name('admin.exams.questions.index');
        Route::post('/exam-questions', [\App\Http\Controllers\Admin\ExamQuestionManagementController::class, 'store'])
            ->name('admin.exams.questions.store');
        Route::delete('/exam-questions/{question}', [\App\Http\Controllers\Admin\ExamQuestionManagementController::class, 'destroy'])
            ->name('admin.exams.questions.destroy');
        Route::get('/grading-configuration', [\App\Http\Controllers\Admin\GradingConfigurationController::class, 'index'])
            ->name('admin.grading.configuration.index');
        Route::patch('/grading-configuration/components', [\App\Http\Controllers\Admin\GradingConfigurationController::class, 'updateComponents'])
            ->name('admin.grading.configuration.components.update');
        Route::delete('/grading-configuration/components/reset-school', [\App\Http\Controllers\Admin\GradingConfigurationController::class, 'resetSchoolComponents'])
            ->name('admin.grading.configuration.components.reset-school');
        Route::patch('/grading-configuration/scales', [\App\Http\Controllers\Admin\GradingConfigurationController::class, 'updateScales'])
            ->name('admin.grading.configuration.scales.update');

        Route::get('/exam-attempts', [ExamTemplateController::class, 'attemptsIndex'])
            ->name('admin.exams.attempts.index');
        Route::delete('/exam-attempts/{attempt}', [ExamTemplateController::class, 'resetAttempt'])
            ->name('admin.exams.attempts.reset');

        Route::get('/scheme', [AdminSchemeOfWorkController::class, 'index'])
            ->name('admin.scheme.index');
        Route::get('/scheme/create', [AdminSchemeOfWorkController::class, 'create'])
            ->name('admin.scheme.create');
        Route::post('/scheme', [AdminSchemeOfWorkController::class, 'store'])
            ->name('admin.scheme.store');
        Route::get('/scheme/{item}/edit', [AdminSchemeOfWorkController::class, 'edit'])
            ->name('admin.scheme.edit');
        Route::patch('/scheme/{item}', [AdminSchemeOfWorkController::class, 'update'])
            ->name('admin.scheme.update');
        Route::delete('/scheme/{item}', [AdminSchemeOfWorkController::class, 'destroy'])
            ->name('admin.scheme.destroy');

        Route::get('/timetables', [AdminTimetableReviewController::class, 'index'])
            ->name('admin.timetables.index');
        Route::post('/timetables/{timetable}/approve', [AdminTimetableReviewController::class, 'approve'])
            ->name('admin.timetables.approve');
        Route::post('/timetables/{timetable}/request-changes', [AdminTimetableReviewController::class, 'requestChanges'])
            ->name('admin.timetables.request-changes');
    });

Route::middleware(['auth', 'verified', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/enrollments', [SchoolRequestAdminController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/{schoolRequest}', [SchoolRequestAdminController::class, 'show'])->name('enrollments.show');
    Route::post('/enrollments/{schoolRequest}/approve', [SchoolRequestAdminController::class, 'approve'])->name('enrollments.approve');
});
