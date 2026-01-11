<?php

use Illuminate\Support\Facades\Route;
use Modules\Teachers\Http\Controllers\TeachersController;

Route::middleware(['api'])->prefix('api/teachers')->name('api.teachers.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('teachers', TeachersController::class);
});
