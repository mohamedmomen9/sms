<?php

use Illuminate\Support\Facades\Route;
use Modules\Subject\Http\Controllers\CourseController;
use Modules\Auth\Http\Middleware\UniversalJwtMiddleware;

Route::prefix('api')->middleware('api')->group(function () {
    // Public routes (if any)
});

// Common Course Endpoint
Route::middleware(['api', UniversalJwtMiddleware::class])->prefix('courses')->group(function () {
    Route::get('current', [CourseController::class, 'index']);
});
