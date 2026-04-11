<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{HomeController, LoginController, OrganizationController, BlogController, ActivityController, AboutController, ContactController, AnalyticsController};
use App\Http\Controllers\Admin\DashboardController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/beranda', [HomeController::class, 'index'])->name('beranda');
Route::get('/organisasi', [OrganizationController::class, 'index'])->name('organisasi');
Route::get('/organisasi/{organization}', [OrganizationController::class, 'show'])->name('organisasi.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/kegiatan', [ActivityController::class, 'index'])->name('kegiatan');
Route::get('/kegiatan/{activity}', [ActivityController::class, 'show'])->name('kegiatan.show');
Route::get('/fasilitas', [\App\Http\Controllers\FacilityController::class, 'index'])->name('fasilitas');
Route::get('/fasilitas/{facility}', [\App\Http\Controllers\FacilityController::class, 'show'])->name('fasilitas.show');
Route::get('/tentang', [AboutController::class, 'index'])->name('tentang');
Route::get('/kontak', [ContactController::class, 'index'])->name('kontak');
Route::post('/kontak', [ContactController::class, 'send'])->name('kontak.send');

// Simple demo page for the data API
Route::get('/data-demo', function () {
    return view('data-demo');
})->name('data.demo');

// Registration routes
Route::get('/daftar/{organization}', [\App\Http\Controllers\RegistrationController::class, 'show'])->name('registration.show');
Route::post('/daftar/{organization}', [\App\Http\Controllers\RegistrationController::class, 'store'])->name('registration.store');

// PPDB routes
Route::get('/ppdb', [\App\Http\Controllers\PPDBController::class, 'index'])->name('ppdb.index');
Route::get('/ppdb/daftar', [\App\Http\Controllers\PPDBController::class, 'create'])->name('ppdb.create');
Route::post('/ppdb/daftar', [\App\Http\Controllers\PPDBController::class, 'store'])->name('ppdb.store');
Route::get('/ppdb/sukses', [\App\Http\Controllers\PPDBController::class, 'success'])->name('ppdb.success');

// Student Registration routes
Route::get('/pendaftaran-siswa', [\App\Http\Controllers\StudentRegistrationController::class, 'index'])->name('student-registration.index');
Route::get('/pendaftaran-siswa/daftar', [\App\Http\Controllers\StudentRegistrationController::class, 'create'])->name('student-registration.create');
Route::post('/pendaftaran-siswa/daftar', [\App\Http\Controllers\StudentRegistrationController::class, 'store'])->name('student-registration.store');
Route::get('/pendaftaran-siswa/sukses', [\App\Http\Controllers\StudentRegistrationController::class, 'success'])->name('student-registration.success');

// Authentication routes

// Admin login routes
use App\Http\Controllers\Admin\AdminLoginController;

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit')->middleware('throttle:10,1');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// OTP routes
Route::get('/otp/verify', [\App\Http\Controllers\OtpController::class, 'show'])->name('otp.show');
Route::post('/otp/verify', [\App\Http\Controllers\OtpController::class, 'verify'])->name('otp.verify');
Route::post('/otp/resend', [\App\Http\Controllers\OtpController::class, 'resend'])->name('otp.resend');
// Email verification route (for tests - uses same controller as OTP)
Route::get('/email/verify', [\App\Http\Controllers\OtpController::class, 'show'])->name('verification.notice');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register routes
Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'show'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('register.store');

// (Email verification & OTP disabled)

// Password reset routes
Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

// Profile routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/request-password-change', [\App\Http\Controllers\ProfileController::class, 'requestPasswordChange'])->name('profile.request-password-change');
    Route::get('/profile/verify-password', [\App\Http\Controllers\ProfileController::class, 'showVerifyPassword'])->name('profile.verify-password');
    Route::post('/profile/verify-password', [\App\Http\Controllers\ProfileController::class, 'verifyPasswordChange']);

    // Profile password routes
    Route::get('/profile/password', [\App\Http\Controllers\ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/password/code', [\App\Http\Controllers\ProfileController::class, 'sendPasswordCode'])->name('profile.password.code');

    // 2FA routes
    Route::get('/2fa', [\App\Http\Controllers\TwoFactorController::class, 'show'])->name('2fa.show');
    Route::post('/2fa/enable', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('2fa.disable');

    // Avatar routes
    Route::post('/avatar/upload', [\App\Http\Controllers\AvatarController::class, 'upload'])->name('avatar.upload');
    Route::get('/avatar/delete', [\App\Http\Controllers\AvatarController::class, 'delete'])->name('avatar.delete');
});

// Admin routes (using custom admin middleware, email verification only required for specific routes)
Route::middleware(['auth', \App\Http\Middleware\AdminOrRedirect::class])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Posts - hanya operasi create, edit, update, destroy yang memerlukan verifikasi
    Route::get('posts', [\App\Http\Controllers\Admin\PostController::class, 'index'])->name('admin.posts.index');
    Route::get('posts/create', [\App\Http\Controllers\Admin\PostController::class, 'create'])->name('admin.posts.create')->middleware('verified');
    Route::post('posts', [\App\Http\Controllers\Admin\PostController::class, 'store'])->name('admin.posts.store')->middleware('verified');
    Route::get('posts/{post}', [\App\Http\Controllers\Admin\PostController::class, 'show'])->name('admin.posts.show');
    Route::get('posts/{post}/edit', [\App\Http\Controllers\Admin\PostController::class, 'edit'])->name('admin.posts.edit')->middleware('verified');
    Route::put('posts/{post}', [\App\Http\Controllers\Admin\PostController::class, 'update'])->name('admin.posts.update')->middleware('verified');
    Route::delete('posts/{post}', [\App\Http\Controllers\Admin\PostController::class, 'destroy'])->name('admin.posts.destroy')->middleware('verified');
    
    // Organizations - hanya operasi create, edit, update, destroy yang memerlukan verifikasi
    Route::get('organizations', [\App\Http\Controllers\Admin\OrganizationController::class, 'index'])->name('admin.organizations.index');
    Route::get('organizations/create', [\App\Http\Controllers\Admin\OrganizationController::class, 'create'])->name('admin.organizations.create')->middleware('verified');
    Route::post('organizations', [\App\Http\Controllers\Admin\OrganizationController::class, 'store'])->name('admin.organizations.store')->middleware('verified');
    Route::get('organizations/{organization}', [\App\Http\Controllers\Admin\OrganizationController::class, 'show'])->name('admin.organizations.show');
    Route::get('organizations/{organization}/edit', [\App\Http\Controllers\Admin\OrganizationController::class, 'edit'])->name('admin.organizations.edit')->middleware('verified');
    Route::put('organizations/{organization}', [\App\Http\Controllers\Admin\OrganizationController::class, 'update'])->name('admin.organizations.update')->middleware('verified');
    Route::delete('organizations/{organization}', [\App\Http\Controllers\Admin\OrganizationController::class, 'destroy'])->name('admin.organizations.destroy')->middleware('verified');
    
    // Activities - hanya operasi create, edit, update, destroy yang memerlukan verifikasi
    Route::get('activities', [\App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('admin.activities.index');
    Route::get('activities/create', [\App\Http\Controllers\Admin\ActivityController::class, 'create'])->name('admin.activities.create')->middleware('verified');
    Route::post('activities', [\App\Http\Controllers\Admin\ActivityController::class, 'store'])->name('admin.activities.store')->middleware('verified');
    Route::get('activities/{activity}', [\App\Http\Controllers\Admin\ActivityController::class, 'show'])->name('admin.activities.show');
    Route::get('activities/{activity}/edit', [\App\Http\Controllers\Admin\ActivityController::class, 'edit'])->name('admin.activities.edit')->middleware('verified');
    Route::put('activities/{activity}', [\App\Http\Controllers\Admin\ActivityController::class, 'update'])->name('admin.activities.update')->middleware('verified');
    Route::delete('activities/{activity}', [\App\Http\Controllers\Admin\ActivityController::class, 'destroy'])->name('admin.activities.destroy')->middleware('verified');
    
    // Statistics - hanya operasi create, edit, update, destroy yang memerlukan verifikasi
    Route::get('statistics', [\App\Http\Controllers\Admin\StatisticController::class, 'index'])->name('admin.statistics.index');
    Route::get('statistics/create', [\App\Http\Controllers\Admin\StatisticController::class, 'create'])->name('admin.statistics.create')->middleware('verified');
    Route::post('statistics', [\App\Http\Controllers\Admin\StatisticController::class, 'store'])->name('admin.statistics.store')->middleware('verified');
    Route::get('statistics/{statistic}', [\App\Http\Controllers\Admin\StatisticController::class, 'show'])->name('admin.statistics.show');
    Route::get('statistics/{statistic}/edit', [\App\Http\Controllers\Admin\StatisticController::class, 'edit'])->name('admin.statistics.edit')->middleware('verified');
    Route::put('statistics/{statistic}', [\App\Http\Controllers\Admin\StatisticController::class, 'update'])->name('admin.statistics.update')->middleware('verified');
    Route::delete('statistics/{statistic}', [\App\Http\Controllers\Admin\StatisticController::class, 'destroy'])->name('admin.statistics.destroy')->middleware('verified');

    // Organization membership management
    Route::get('organizations/{organization}/members', [\App\Http\Controllers\Admin\MemberController::class, 'index'])->name('admin.organizations.members.index');
    Route::get('organizations/{organization}/members/create', [\App\Http\Controllers\Admin\MemberController::class, 'create'])->name('admin.organizations.members.create')->middleware('verified');
    Route::post('organizations/{organization}/members', [\App\Http\Controllers\Admin\MemberController::class, 'store'])->name('admin.organizations.members.store')->middleware('verified');
    Route::get('organizations/{organization}/members/{member}', [\App\Http\Controllers\Admin\MemberController::class, 'show'])->name('admin.organizations.members.show');
    Route::get('organizations/{organization}/members/{member}/edit', [\App\Http\Controllers\Admin\MemberController::class, 'edit'])->name('admin.organizations.members.edit')->middleware('verified');
    Route::put('organizations/{organization}/members/{member}', [\App\Http\Controllers\Admin\MemberController::class, 'update'])->name('admin.organizations.members.update')->middleware('verified');
    Route::delete('organizations/{organization}/members/{member}', [\App\Http\Controllers\Admin\MemberController::class, 'destroy'])->name('admin.organizations.members.destroy')->middleware('verified');
    Route::post('organizations/{organization}/members/{member}/promote', [\App\Http\Controllers\Admin\MemberController::class, 'promote'])->name('admin.organizations.members.promote')->middleware('verified');
    Route::post('organizations/{organization}/members/{member}/status', [\App\Http\Controllers\Admin\MemberController::class, 'changeStatus'])->name('admin.organizations.members.status')->middleware('verified');
    Route::post('organizations/{organization}/members/bulk', [\App\Http\Controllers\Admin\MemberController::class, 'bulkAction'])->name('admin.organizations.members.bulk')->middleware('verified');

    // Organization period management
    Route::get('organizations/{organization}/periods', [\App\Http\Controllers\Admin\OrganizationPeriodController::class, 'index'])->name('admin.organizations.periods.index');
    Route::get('organizations/{organization}/periods/create', [\App\Http\Controllers\Admin\OrganizationPeriodController::class, 'create'])->name('admin.organizations.periods.create')->middleware('verified');
    Route::post('organizations/{organization}/periods', [\App\Http\Controllers\Admin\OrganizationPeriodController::class, 'store'])->name('admin.organizations.periods.store')->middleware('verified');
    Route::get('organizations/{organization}/periods/{period}', [\App\Http\Controllers\Admin\OrganizationPeriodController::class, 'show'])->name('admin.organizations.periods.show');
    Route::get('organizations/{organization}/periods/{period}/edit', [\App\Http\Controllers\Admin\OrganizationPeriodController::class, 'edit'])->name('admin.organizations.periods.edit')->middleware('verified');
    Route::put('organizations/{organization}/periods/{period}', [\App\Http\Controllers\Admin\OrganizationPeriodController::class, 'update'])->name('admin.organizations.periods.update')->middleware('verified');
    Route::delete('organizations/{organization}/periods/{period}', [\App\Http\Controllers\Admin\OrganizationPeriodController::class, 'destroy'])->name('admin.organizations.periods.destroy')->middleware('verified');
    Route::post('organizations/{organization}/periods/{period}/activate', [\App\Http\Controllers\Admin\OrganizationPeriodController::class, 'activate'])->name('admin.organizations.periods.activate')->middleware('verified');
    Route::put('organizations/{organization}/periods/{period}/leadership', [\App\Http\Controllers\Admin\OrganizationPeriodController::class, 'updateLeadership'])->name('admin.organizations.periods.leadership')->middleware('verified');
    
    // Students - hanya operasi create, edit, update, destroy yang memerlukan verifikasi
    Route::get('students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('admin.students.index');
    Route::get('students/create', [\App\Http\Controllers\Admin\StudentController::class, 'create'])->name('admin.students.create')->middleware('verified');
    Route::post('students', [\App\Http\Controllers\Admin\StudentController::class, 'store'])->name('admin.students.store')->middleware('verified');
    Route::get('students/{student}', [\App\Http\Controllers\Admin\StudentController::class, 'show'])->name('admin.students.show');
    Route::get('students/{student}/edit', [\App\Http\Controllers\Admin\StudentController::class, 'edit'])->name('admin.students.edit')->middleware('verified');
    Route::put('students/{student}', [\App\Http\Controllers\Admin\StudentController::class, 'update'])->name('admin.students.update')->middleware('verified');
    Route::delete('students/{student}', [\App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('admin.students.destroy')->middleware('verified');
    
    // Teachers - hanya operasi create, edit, update, destroy yang memerlukan verifikasi
    Route::get('teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'index'])->name('admin.teachers.index');
    Route::get('teachers/create', [\App\Http\Controllers\Admin\TeacherController::class, 'create'])->name('admin.teachers.create')->middleware('verified');
    Route::post('teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('admin.teachers.store')->middleware('verified');
    Route::get('teachers/{teacher}', [\App\Http\Controllers\Admin\TeacherController::class, 'show'])->name('admin.teachers.show');
    Route::get('teachers/{teacher}/edit', [\App\Http\Controllers\Admin\TeacherController::class, 'edit'])->name('admin.teachers.edit')->middleware('verified');
    Route::put('teachers/{teacher}', [\App\Http\Controllers\Admin\TeacherController::class, 'update'])->name('admin.teachers.update')->middleware('verified');
    Route::delete('teachers/{teacher}', [\App\Http\Controllers\Admin\TeacherController::class, 'destroy'])->name('admin.teachers.destroy')->middleware('verified');
    
    // Facilities - hanya operasi create, edit, update, destroy yang memerlukan verifikasi
    Route::get('facilities', [\App\Http\Controllers\Admin\FacilityController::class, 'index'])->name('admin.facilities.index');
    Route::get('facilities/create', [\App\Http\Controllers\Admin\FacilityController::class, 'create'])->name('admin.facilities.create')->middleware('verified');
    Route::post('facilities', [\App\Http\Controllers\Admin\FacilityController::class, 'store'])->name('admin.facilities.store')->middleware('verified');
    Route::get('facilities/{facility}', [\App\Http\Controllers\Admin\FacilityController::class, 'show'])->name('admin.facilities.show');
    Route::get('facilities/{facility}/edit', [\App\Http\Controllers\Admin\FacilityController::class, 'edit'])->name('admin.facilities.edit')->middleware('verified');
    Route::put('facilities/{facility}', [\App\Http\Controllers\Admin\FacilityController::class, 'update'])->name('admin.facilities.update')->middleware('verified');
    Route::delete('facilities/{facility}', [\App\Http\Controllers\Admin\FacilityController::class, 'destroy'])->name('admin.facilities.destroy')->middleware('verified');
    
    // Messages - hanya operasi destroy yang memerlukan verifikasi
    Route::get('messages', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('admin.messages.index');
    Route::get('messages/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'show'])->name('admin.messages.show');
    Route::delete('messages/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'destroy'])->name('admin.messages.destroy')->middleware('verified');
    
    // Settings - hanya operasi update yang memerlukan verifikasi
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update')->middleware('verified');
    
    // Registrations - hanya operasi destroy dan update-status yang memerlukan verifikasi
    Route::get('registrations', [\App\Http\Controllers\Admin\RegistrationController::class, 'index'])->name('admin.registrations.index');
    Route::get('registrations/{registration}', [\App\Http\Controllers\Admin\RegistrationController::class, 'show'])->name('admin.registrations.show');
    Route::patch('registrations/{registration}/status', [\App\Http\Controllers\Admin\RegistrationController::class, 'updateStatus'])->name('admin.registrations.update-status')->middleware('verified');
    Route::delete('registrations/{registration}', [\App\Http\Controllers\Admin\RegistrationController::class, 'destroy'])->name('admin.registrations.destroy')->middleware('verified');
    
    // Users - hanya operasi create dan store yang memerlukan verifikasi
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create')->middleware('verified');
    Route::post('users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store')->middleware('verified');
    Route::get('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    Route::get('users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit')->middleware('verified');
    Route::put('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update')->middleware('verified');
    Route::delete('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy')->middleware('verified');
    
    // PPDB - hanya operasi create, edit, update, destroy yang memerlukan verifikasi
    Route::get('ppdb', [\App\Http\Controllers\Admin\PPDBController::class, 'index'])->name('admin.ppdb.index');
    Route::get('ppdb/create', [\App\Http\Controllers\Admin\PPDBController::class, 'create'])->name('admin.ppdb.create')->middleware('verified');
    Route::post('ppdb', [\App\Http\Controllers\Admin\PPDBController::class, 'store'])->name('admin.ppdb.store')->middleware('verified');
    Route::get('ppdb/{ppdb}', [\App\Http\Controllers\Admin\PPDBController::class, 'show'])->name('admin.ppdb.show');
    Route::get('ppdb/{ppdb}/edit', [\App\Http\Controllers\Admin\PPDBController::class, 'edit'])->name('admin.ppdb.edit')->middleware('verified');
    Route::put('ppdb/{ppdb}', [\App\Http\Controllers\Admin\PPDBController::class, 'update'])->name('admin.ppdb.update')->middleware('verified');
    Route::delete('ppdb/{ppdb}', [\App\Http\Controllers\Admin\PPDBController::class, 'destroy'])->name('admin.ppdb.destroy')->middleware('verified');

    // Student Registration management
    Route::get('student-registrations', [\App\Http\Controllers\StudentRegistrationController::class, 'adminIndex'])->name('admin.student-registrations.index');
    Route::get('student-registrations/{registration}', [\App\Http\Controllers\StudentRegistrationController::class, 'show'])->name('admin.student-registrations.show');
    Route::post('student-registrations/{registration}/approve', [\App\Http\Controllers\StudentRegistrationController::class, 'approve'])->name('admin.student-registrations.approve')->middleware('verified');
    Route::post('student-registrations/{registration}/reject', [\App\Http\Controllers\StudentRegistrationController::class, 'reject'])->name('admin.student-registrations.reject')->middleware('verified');
    Route::get('student-registrations/export', [\App\Http\Controllers\StudentRegistrationController::class, 'export'])->name('student-registrations.export')->middleware('verified');

    // Security audit routes
    Route::get('security/audit', [\App\Http\Controllers\Admin\SecurityAuditController::class, 'index'])
        ->name('admin.security.audit');
    Route::get('security/export', [\App\Http\Controllers\Admin\SecurityAuditController::class, 'export'])
        ->name('admin.security.export');

    // Analytics routes
    Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('admin.analytics.index');
    Route::get('analytics/organization/{organization}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'organization'])->name('admin.analytics.organization');
    Route::get('analytics/reports', [\App\Http\Controllers\Admin\AnalyticsController::class, 'reports'])->name('admin.analytics.reports');
    Route::post('analytics/reports/generate', [\App\Http\Controllers\Admin\AnalyticsController::class, 'generateReport'])->name('admin.analytics.reports.generate');
    Route::get('analytics/reports/{report}/download', [\App\Http\Controllers\Admin\AnalyticsController::class, 'downloadReport'])->name('admin.analytics.reports.download');
    Route::delete('analytics/reports/{report}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'deleteReport'])->name('admin.analytics.reports.delete');
    Route::get('analytics/performance', [\App\Http\Controllers\Admin\AnalyticsController::class, 'performance'])->name('admin.analytics.performance');
    Route::get('analytics/compare', [\App\Http\Controllers\Admin\AnalyticsController::class, 'compare'])->name('admin.analytics.compare');
    Route::post('analytics/compare', [\App\Http\Controllers\Admin\AnalyticsController::class, 'compareResults'])->name('admin.analytics.compare.results');

    // Galleries - hanya operasi create, edit, update, destroy yang memerlukan verifikasi
    Route::get('galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'index'])->name('admin.galleries.index');
    Route::get('galleries/create', [\App\Http\Controllers\Admin\GalleryController::class, 'create'])->name('admin.galleries.create')->middleware('verified');
    Route::post('galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'store'])->name('admin.galleries.store')->middleware('verified');
    Route::get('galleries/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'show'])->name('admin.galleries.show');
    Route::get('galleries/{gallery}/edit', [\App\Http\Controllers\Admin\GalleryController::class, 'edit'])->name('admin.galleries.edit')->middleware('verified');
    Route::put('galleries/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'update'])->name('admin.galleries.update')->middleware('verified');
    Route::delete('galleries/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroy'])->name('admin.galleries.destroy')->middleware('verified');

    // Values - hanya operasi create, edit, update, destroy yang memerlukan verifikasi
    Route::get('values', [\App\Http\Controllers\Admin\ValueController::class, 'index'])->name('admin.values.index');
    Route::get('values/create', [\App\Http\Controllers\Admin\ValueController::class, 'create'])->name('admin.values.create')->middleware('verified');
    Route::post('values', [\App\Http\Controllers\Admin\ValueController::class, 'store'])->name('admin.values.store')->middleware('verified');
    Route::get('values/{value}', [\App\Http\Controllers\Admin\ValueController::class, 'show'])->name('admin.values.show');
    Route::get('values/{value}/edit', [\App\Http\Controllers\Admin\ValueController::class, 'edit'])->name('admin.values.edit')->middleware('verified');
    Route::put('values/{value}', [\App\Http\Controllers\Admin\ValueController::class, 'update'])->name('admin.values.update')->middleware('verified');
    Route::delete('values/{value}', [\App\Http\Controllers\Admin\ValueController::class, 'destroy'])->name('admin.values.destroy')->middleware('verified');

    // Categories - hanya operasi create, edit, update, destroy yang memerlukan verifikasi
    Route::get('categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('admin.categories.create')->middleware('verified');
    Route::post('categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store')->middleware('verified');
    Route::get('categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('admin.categories.show');
    Route::get('categories/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('admin.categories.edit')->middleware('verified');
    Route::put('categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update')->middleware('verified');
    Route::delete('categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.categories.destroy')->middleware('verified');
});
