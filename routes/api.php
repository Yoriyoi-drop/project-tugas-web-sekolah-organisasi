<?php

use Illuminate\Support\Facades\Route;

// Health check endpoints (no authentication required)
Route::get('/health', [App\Http\Controllers\Api\HealthController::class, 'health']);
Route::get('/health/detailed', [App\Http\Controllers\Api\HealthController::class, 'detailed']);

// API Version 1 Routes
Route::prefix('v1')->group(function () {
    // Student API endpoints v1
    Route::middleware(['auth:sanctum', 'throttle:students-api', 'api.version:v1'])->prefix('students')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\V1\StudentController::class, 'index']);
        Route::post('/', [App\Http\Controllers\Api\V1\StudentController::class, 'store']);
        Route::get('/{id}', [App\Http\Controllers\Api\V1\StudentController::class, 'show']);
        Route::put('/{id}', [App\Http\Controllers\Api\V1\StudentController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\Api\V1\StudentController::class, 'destroy']);
    });
});

// API Version 2 Routes (Latest)
Route::prefix('v2')->group(function () {
    // Student API endpoints v2
    Route::middleware(['auth:sanctum', 'throttle:students-api', 'api.version:v2'])->prefix('students')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\V2\StudentController::class, 'index']);
        Route::post('/', [App\Http\Controllers\Api\V2\StudentController::class, 'store']);
        Route::get('/{id}', [App\Http\Controllers\Api\V2\StudentController::class, 'show']);
        Route::put('/{id}', [App\Http\Controllers\Api\V2\StudentController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\Api\V2\StudentController::class, 'destroy']);
    });
});

// Legacy API routes (redirect to v2)
Route::middleware(['auth:sanctum', 'throttle:students-api'])->prefix('students')->group(function () {
    Route::get('/', function () {
        return redirect()->route('api.v2.students.index');
    });
    Route::post('/', function () {
        return redirect()->route('api.v2.students.store');
    });
    Route::get('/{id}', function ($id) {
        return redirect()->route('api.v2.students.show', $id);
    });
    Route::put('/{id}', function ($id) {
        return redirect()->route('api.v2.students.update', $id);
    });
    Route::delete('/{id}', function ($id) {
        return redirect()->route('api.v2.students.destroy', $id);
    });
});

// Admin API endpoints with higher rate limits
Route::middleware(['auth:sanctum', 'throttle:admin-api'])->prefix('admin')->group(function () {
    // Admin endpoints can go here
});

// Data API endpoints - All protected with authentication and rate limiting
Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('data')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Data\LoginDataController::class, 'index']);
    Route::get('/profile/{id}', [\App\Http\Controllers\Data\ProfileDataController::class, 'show']);
    Route::get('/ppdb', [\App\Http\Controllers\Data\PpdbDataController::class, 'index']);
    Route::get('/ppdb/{id}', [\App\Http\Controllers\Data\PpdbDataController::class, 'show']);
    Route::get('/bagus', [\App\Http\Controllers\Data\BagusDataController::class, 'index']);
});
