<?php

use Illuminate\Support\Facades\Route;
use Modules\Students\Http\Controllers\TutorialController;

use Modules\Auth\Http\Middleware\UniversalJwtMiddleware;
use Modules\System\Http\Middleware\InjectUserContext;
use Modules\Students\Http\Controllers\StudentImageController;

Route::prefix('v1')->middleware(['api'])->group(function () {

    Route::prefix('students')->name('api.students.')->group(function () {
        // Define your module API routes here
        // Example:
        // Route::apiResource('students', StudentsController::class);
    });

    Route::prefix('tutorials')->group(function () {
        Route::middleware([UniversalJwtMiddleware::class])->group(function () {
            Route::get('{key}/status', [TutorialController::class, 'status']);
            Route::post('{key}/complete', [TutorialController::class, 'complete']);
        });
    });

    Route::prefix('student-images')->middleware([UniversalJwtMiddleware::class, InjectUserContext::class])->group(function () {
        Route::get('eligibility', [StudentImageController::class, 'eligibility']);
        Route::post('/', [StudentImageController::class, 'update']);
        Route::get('{studentId}', [StudentImageController::class, 'show']);
    });
});
