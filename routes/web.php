<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolRequestController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/admin.php';
require __DIR__.'/school.php';
require __DIR__.'/instructor.php';
require __DIR__.'/training.php';
require __DIR__.'/store.php';
require __DIR__.'/class-teacher.php';

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
Route::middleware('signed')->group(function () {
    Route::get('/become-an-instructor/{screening}/biodata', [\App\Http\Controllers\Instructor\InstructorOnboardingController::class, 'create'])
        ->name('instructor.screening.biodata.create');
    Route::post('/become-an-instructor/{screening}/biodata', [\App\Http\Controllers\Instructor\InstructorOnboardingController::class, 'store'])
        ->name('instructor.screening.biodata.store');
    Route::get('/school/register/{schoolRequest}', [\App\Http\Controllers\School\PortalOnboardingController::class, 'create'])
        ->name('school.portal.onboarding.create');
    Route::post('/school/register/{schoolRequest}', [\App\Http\Controllers\School\PortalOnboardingController::class, 'store'])
        ->name('school.portal.onboarding.store');
});
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

$mainDomain = parse_url(config('app.url'), PHP_URL_HOST) ?: 'genchess.ng';

Route::domain($mainDomain)->get('/dashboard', function () {
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

require __DIR__.'/auth.php';

