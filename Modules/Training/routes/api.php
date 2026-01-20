<?php

use Illuminate\Support\Facades\Route;
use Modules\Training\Http\Controllers\TrainingController;

Route::middleware(['api'])->prefix('api/training')->name('api.training.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('trainings', TrainingController::class);
});
