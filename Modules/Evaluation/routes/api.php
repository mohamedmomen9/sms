<?php

use Illuminate\Support\Facades\Route;
use Modules\Evaluation\Http\Controllers\EvaluationController;

Route::middleware(['api'])->prefix('api/evaluation')->name('api.evaluation.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('evaluations', EvaluationController::class);
});
