<?php

use Illuminate\Support\Facades\Route;
use Modules\Curriculum\Http\Controllers\CurriculumController;

Route::prefix('v1')->middleware(['api'])->group(function () {
    
    Route::prefix('curriculum')->name('api.curriculum.')->group(function () {
        // Define your module API routes here
        // Example:
        // Route::apiResource('curricula', CurriculumController::class);
    });
});
