<?php

use Illuminate\Support\Facades\Route;
use Modules\Faculty\Http\Controllers\FacultyController;

Route::prefix('v1')->middleware(['api'])->group(function () {
    
    Route::prefix('faculty')->name('api.faculty.')->group(function () {
        // Define your module API routes here
        // Example:
        // Route::apiResource('faculties', FacultyController::class);
    });
});
