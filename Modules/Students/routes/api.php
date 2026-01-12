<?php

use Illuminate\Support\Facades\Route;
use Modules\Students\Http\Controllers\StudentsController;

Route::middleware(['api'])->prefix('students')->name('api.students.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('students', StudentsController::class);
});

// Auth routes moved to Modules/Auth


// Auth routes moved to Modules/Auth
// Course routes moved to Modules/Subject

