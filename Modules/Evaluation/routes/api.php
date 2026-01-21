<?php

use Illuminate\Support\Facades\Route;
use Modules\Evaluation\Http\Controllers\EvaluationController;

Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::prefix('evaluation')->group(function () {
        Route::get('structure', [EvaluationController::class, 'structure']);
        Route::get('courses', [EvaluationController::class, 'courses']);
        Route::post('submit', [EvaluationController::class, 'submit']);
    });
});
