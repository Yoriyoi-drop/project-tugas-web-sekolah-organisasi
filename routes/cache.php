<?php

use Illuminate\Support\Facades\Route;

// Public routes that can be cached
Route::middleware('response.cache')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/beranda', [App\Http\Controllers\HomeController::class, 'index'])->name('beranda');
    Route::get('/organisasi', [App\Http\Controllers\OrganizationController::class, 'index'])->name('organisasi');
    Route::get('/organisasi/{id}', [App\Http\Controllers\OrganizationController::class, 'show'])->whereNumber('id')->name('organisasi.show');
    Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog');
    Route::get('/kegiatan', [App\Http\Controllers\ActivityController::class, 'index'])->name('kegiatan');
    Route::get('/fasilitas', [\App\Http\Controllers\FacilityController::class, 'index'])->name('fasilitas');
    Route::get('/fasilitas/{facility}', [\App\Http\Controllers\FacilityController::class, 'show'])->name('fasilitas.show');
    Route::get('/tentang', [App\Http\Controllers\AboutController::class, 'index'])->name('tentang');
});
