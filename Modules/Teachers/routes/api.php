<?php

use Illuminate\Support\Facades\Route;
use Modules\Teachers\Http\Controllers\TeachersController;

Route::prefix('v1')->middleware(['api'])->group(function () {
    
    Route::prefix('teachers')->name('api.teachers.')->group(function () {
        // Define your module API routes here
        // Example:
        // Route::apiResource('teachers', TeachersController::class);
    });
});
