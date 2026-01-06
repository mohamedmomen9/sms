<?php

use Illuminate\Support\Facades\Route;
use Modules\Faculty\Http\Controllers\FacultyController;

Route::middleware(['api'])->prefix('api/faculty')->name('api.faculty.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('faculties', FacultyController::class);
});
