<?php

use Illuminate\Support\Facades\Route;
use Modules\Subject\Http\Controllers\CourseController;
use Modules\Auth\Http\Middleware\UniversalJwtMiddleware;

Route::prefix('v1')->middleware('api')->group(function () {

    // Common Course Endpoint
    Route::prefix('courses')->middleware(UniversalJwtMiddleware::class)->group(function () {
        Route::get('current', [CourseController::class, 'index']);
    });
});
