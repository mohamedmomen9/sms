<?php

use Illuminate\Support\Facades\Route;
use Modules\Subject\Http\Controllers\SubjectController;

Route::middleware(['api'])->prefix('api/subject')->name('api.subject.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('subjects', SubjectController::class);
});
