<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SchoolApprovalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\School\StudentController;
use App\Http\Controllers\School\AttendanceController;
use App\Http\Controllers\School\ClassTeacherController;
use App\Http\Controllers\School\ClassTeacherFeedbackController;
use App\Http\Controllers\School\ExamAssignmentController;
use App\Http\Controllers\SchoolRequestController;
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
use Illuminate\Support\Facades\Auth;

Route::get('/', [\App\Http\Controllers\Public\PageController::class, 'home'])->name('home');
Route::get('/about', [\App\Http\Controllers\Public\PageController::class, 'about'])->name('about');
Route::get('/contact', [\App\Http\Controllers\Public\PageController::class, 'contact'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\Public\ContactController::class, 'store'])->name('contact.store');
Route::get('/chess-in-schools', [\App\Http\Controllers\Public\PageController::class, 'chessInSchools'])
    ->name('chess.in.schools');
Route::get('/chess-in-schools/primary-1-6', [\App\Http\Controllers\Public\PageController::class, 'chessInSchoolsPrimary'])
    ->name('chess.in.schools.primary');
Route::get('/chess-in-schools/jss-1-3', [\App\Http\Controllers\Public\PageController::class, 'chessInSchoolsJss'])
    ->name('chess.in.schools.jss');
Route::get('/chess-communities-homes', [\App\Http\Controllers\Public\PageController::class, 'chessCommunitiesHomes'])
    ->name('chess.communities.homes');
Route::get('/instructor-training', [\App\Http\Controllers\Public\PageController::class, 'instructorTraining'])
    ->name('instructor.training');
Route::get('/training/preview', [\App\Http\Controllers\Public\TrainingCheckoutController::class, 'preview'])
    ->name('training.preview');
Route::middleware('auth')->group(function () {
    Route::get('/training/checkout', [\App\Http\Controllers\Public\TrainingCheckoutController::class, 'checkout'])
        ->name('training.checkout');
    Route::post('/training/checkout/apply-coupon', [\App\Http\Controllers\Public\TrainingCheckoutController::class, 'applyCoupon'])
        ->name('training.checkout.apply-coupon');
    Route::post('/training/checkout/initialize', [\App\Http\Controllers\Public\TrainingCheckoutController::class, 'initialize'])
        ->name('training.checkout.initialize');
})->middleware('verified');
Route::get('/training/checkout/callback', [\App\Http\Controllers\Public\TrainingCheckoutController::class, 'callback'])
    ->name('training.checkout.callback');
Route::post('/payments/paystack/webhook', [\App\Http\Controllers\Public\TrainingCheckoutController::class, 'webhook'])
    ->name('payments.paystack.webhook');
Route::get('/careers', [\App\Http\Controllers\Public\CareerController::class, 'index'])->name('careers');
Route::get('/careers/chess-instructors', [\App\Http\Controllers\Public\PageController::class, 'careersInstructors'])
    ->name('careers.instructors');
Route::get('/careers/coordinators', [\App\Http\Controllers\Public\PageController::class, 'careersCoordinators'])
    ->name('careers.coordinators');
Route::get('/careers/marketers', [\App\Http\Controllers\Public\PageController::class, 'careersMarketers'])
    ->name('careers.marketers');
Route::get('/become-an-instructor', [\App\Http\Controllers\Public\InstructorScreeningController::class, 'create'])
    ->name('instructor.screening.create');
Route::post('/become-an-instructor', [\App\Http\Controllers\Public\InstructorScreeningController::class, 'store'])
    ->name('instructor.screening.store');
Route::get('/become-an-instructor/result', [\App\Http\Controllers\Public\InstructorScreeningController::class, 'result'])
    ->name('instructor.screening.result');
Route::get('/careers/{job:slug}', [\App\Http\Controllers\Public\CareerController::class, 'show'])
    ->name('careers.show');
Route::post('/careers/{job:slug}/apply', [\App\Http\Controllers\Public\CareerController::class, 'apply'])
    ->name('careers.apply');
Route::get('/products', [\App\Http\Controllers\Public\PageController::class, 'products'])->name('products');
Route::get('/products/chess-boards', [\App\Http\Controllers\Public\PageController::class, 'productsBoards'])
    ->name('products.boards');
Route::get('/products/chess-clocks', [\App\Http\Controllers\Public\PageController::class, 'productsClocks'])
    ->name('products.clocks');
Route::get('/products/books-materials', [\App\Http\Controllers\Public\PageController::class, 'productsBooks'])
    ->name('products.books');
Route::get('/store', [\App\Http\Controllers\Public\StoreController::class, 'index'])->name('store.index');
Route::get('/store/category/{category:slug}', [\App\Http\Controllers\Public\StoreController::class, 'category'])
    ->name('store.category');
Route::get('/store/product/{product:slug}', [\App\Http\Controllers\Public\StoreController::class, 'product'])
    ->name('store.product');
Route::get('/cart', [\App\Http\Controllers\Public\CartController::class, 'index'])->name('store.cart');
Route::post('/cart/add/{product}', [\App\Http\Controllers\Public\CartController::class, 'add'])->name('store.cart.add');
Route::patch('/cart/item/{lineKey}', [\App\Http\Controllers\Public\CartController::class, 'update'])->name('store.cart.update');
Route::delete('/cart/item/{lineKey}', [\App\Http\Controllers\Public\CartController::class, 'remove'])->name('store.cart.remove');
Route::get('/checkout', [\App\Http\Controllers\Public\StoreCheckoutController::class, 'show'])->name('store.checkout');
Route::post('/checkout', [\App\Http\Controllers\Public\StoreCheckoutController::class, 'placeOrder'])->name('store.checkout.place');
Route::get('/checkout/callback', [\App\Http\Controllers\Public\StoreCheckoutController::class, 'callback'])
    ->name('store.checkout.callback');
Route::post('/payments/store/paystack/webhook', [\App\Http\Controllers\Public\StoreCheckoutController::class, 'webhook'])
    ->name('store.paystack.webhook');
Route::get('/checkout/success/{order}', [\App\Http\Controllers\Public\StoreCheckoutController::class, 'success'])
    ->name('store.checkout.success');
Route::post('/store/bulk-order', [\App\Http\Controllers\Public\BulkOrderController::class, 'store'])
    ->name('store.bulk-order.store');
Route::get('/tournaments', [\App\Http\Controllers\Public\PageController::class, 'tournaments'])->name('tournaments');
Route::get('/register-school', [\App\Http\Controllers\Public\PageController::class, 'registerSchool'])
    ->name('register.school');
Route::get('/verify-certificate', [\App\Http\Controllers\Public\CertificateVerificationController::class, 'index'])
    ->name('certificate.verify');
Route::post('/verify-certificate', [\App\Http\Controllers\Public\CertificateVerificationController::class, 'show'])
    ->name('certificate.verify.show');
Route::get('/online-exam', [\App\Http\Controllers\Public\OnlineExamController::class, 'showCodeForm'])
    ->name('public.exams.code');
Route::post('/online-exam', [\App\Http\Controllers\Public\OnlineExamController::class, 'showByCode'])
    ->name('public.exams.lookup');
Route::get('/online-exam/{examCode}', [\App\Http\Controllers\Public\OnlineExamController::class, 'showExam'])
    ->name('public.exams.take');
Route::post('/online-exam/{examCode}/submit', [\App\Http\Controllers\Public\OnlineExamController::class, 'submit'])
    ->name('public.exams.submit');
Route::get('/online-exam/{examCode}/result/{attempt}', [\App\Http\Controllers\Public\OnlineExamController::class, 'result'])
    ->name('public.exams.result');
Route::get('/verify/{certificate_number}', [\App\Http\Controllers\CertificateController::class, 'verify'])
    ->name('certificates.verify');
Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::post('/certificates/generate', [\App\Http\Controllers\CertificateController::class, 'generate'])
        ->name('certificates.generate');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'super_admin') {
        return redirect('/admin/dashboard');
    }

    if ($user->role === 'school_admin') {
        return redirect('/school/dashboard');
    }

    if ($user->role === 'instructor') {
        return redirect('/instructor/dashboard');
    }

    if ($user->role === 'class_teacher') {
        return redirect('/class-teacher/dashboard');
    }

    abort(403);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::redirect('/schools/register', '/register-school')->name('schools.register.legacy');
Route::post('/enroll', [SchoolRequestController::class, 'store'])->name('school.enroll');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notificationId}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
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

Route::middleware(['auth', 'verified', 'classteacher'])->prefix('class-teacher')->name('class-teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ClassTeacher\DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/timetable', [\App\Http\Controllers\ClassTeacher\TimetableController::class, 'index'])
        ->name('timetable.index');
    Route::get('/feedback', [\App\Http\Controllers\ClassTeacher\FeedbackController::class, 'create'])
        ->name('feedback.create');
    Route::post('/feedback', [\App\Http\Controllers\ClassTeacher\FeedbackController::class, 'store'])
        ->name('feedback.store');
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

require __DIR__.'/auth.php';
