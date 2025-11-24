<?php

use Illuminate\Support\Facades\Route;

// Data API endpoints - All protected with authentication and rate limiting
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/data/login', [\App\Http\Controllers\Data\LoginDataController::class, 'index']);
    Route::get('/data/profile/{id}', [\App\Http\Controllers\Data\ProfileDataController::class, 'show']);
    Route::get('/data/ppdb', [\App\Http\Controllers\Data\PpdbDataController::class, 'index']);
    Route::get('/data/ppdb/{id}', [\App\Http\Controllers\Data\PpdbDataController::class, 'show']);
    Route::get('/data/bagus', [\App\Http\Controllers\Data\BagusDataController::class, 'index']);
});
