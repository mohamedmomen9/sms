<?php

use Illuminate\Support\Facades\Route;
use Modules\Students\Http\Controllers\StudentsController;

Route::middleware(['api'])->prefix('api/students')->name('api.students.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('students', StudentsController::class);
});
