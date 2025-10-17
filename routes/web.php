<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{HomeController, LoginController, OrganizationController, BlogController, ActivityController, AboutController, ContactController};
use App\Http\Controllers\Admin\DashboardController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/beranda', [HomeController::class, 'index'])->name('beranda');
Route::get('/organisasi', [OrganizationController::class, 'index'])->name('organisasi');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/kegiatan', [ActivityController::class, 'index'])->name('kegiatan');
Route::get('/tentang', [AboutController::class, 'index'])->name('tentang');
Route::get('/kontak', [ContactController::class, 'index'])->name('kontak');
Route::post('/kontak', [ContactController::class, 'send'])->name('kontak.send');

// Registration routes
Route::get('/daftar/{organization}', [\App\Http\Controllers\RegistrationController::class, 'show'])->name('registration.show');
Route::post('/daftar/{organization}', [\App\Http\Controllers\RegistrationController::class, 'store'])->name('registration.store');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/request-password-change', [\App\Http\Controllers\ProfileController::class, 'requestPasswordChange'])->name('profile.request-password-change');
    Route::get('/profile/verify-password', [\App\Http\Controllers\ProfileController::class, 'showVerifyPassword'])->name('profile.verify-password');
    Route::post('/profile/verify-password', [\App\Http\Controllers\ProfileController::class, 'verifyPasswordChange']);
    
    // 2FA routes
    Route::get('/2fa', [\App\Http\Controllers\TwoFactorController::class, 'show'])->name('2fa.show');
    Route::post('/2fa/enable', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('2fa.disable');
    
    // Avatar routes
    Route::post('/avatar/upload', [\App\Http\Controllers\AvatarController::class, 'upload'])->name('avatar.upload');
    Route::get('/avatar/delete', [\App\Http\Controllers\AvatarController::class, 'delete'])->name('avatar.delete');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    Route::resource('organizations', \App\Http\Controllers\Admin\OrganizationController::class);
    Route::resource('activities', \App\Http\Controllers\Admin\ActivityController::class);
    Route::resource('statistics', \App\Http\Controllers\Admin\StatisticController::class);
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);
    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
    Route::resource('messages', \App\Http\Controllers\Admin\MessageController::class)->only(['index', 'show', 'destroy']);
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::resource('registrations', \App\Http\Controllers\Admin\RegistrationController::class)->only(['index', 'show', 'destroy']);
    Route::patch('registrations/{registration}/status', [\App\Http\Controllers\Admin\RegistrationController::class, 'updateStatus'])->name('registrations.update-status');
});
