<?php

use Illuminate\Support\Facades\Route;
use Modules\Academic\Http\Controllers\AcademicController;

Route::middleware(['api'])->prefix('api/academic')->name('api.academic.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('academics', AcademicController::class);
});
