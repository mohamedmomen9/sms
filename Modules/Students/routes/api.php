<?php

use Illuminate\Support\Facades\Route;
use Modules\Students\Http\Controllers\StudentsController;

Route::prefix('v1')->middleware(['api'])->group(function () {
    
    Route::prefix('students')->name('api.students.')->group(function () {
        // Define your module API routes here
        // Example:
        // Route::apiResource('students', StudentsController::class);
    });
});
