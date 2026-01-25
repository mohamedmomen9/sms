<?php

use Illuminate\Support\Facades\Route;
use Modules\Students\Http\Controllers\TutorialController;

Route::prefix('v1')->middleware(['api'])->group(function () {

    Route::prefix('students')->name('api.students.')->group(function () {
        // Define your module API routes here
        // Example:
        // Route::apiResource('students', StudentsController::class);
    });

    Route::prefix('tutorials')->group(function () {
        Route::middleware(['auth:api'])->group(function () {
            Route::get('{key}/status', [TutorialController::class, 'status']);
            Route::post('{key}/complete', [TutorialController::class, 'complete']);
        });
    });
});
