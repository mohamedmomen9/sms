<?php

use Illuminate\Support\Facades\Route;
use Modules\Evaluation\Http\Controllers\EvaluationController;
use Modules\Auth\Http\Middleware\UniversalJwtMiddleware;

Route::prefix('v1')->middleware([UniversalJwtMiddleware::class])->group(function () {
    Route::prefix('evaluation')->group(function () {
        Route::get('structure', [EvaluationController::class, 'structure']);
        Route::get('courses', [EvaluationController::class, 'courses']);
        Route::post('submit', [EvaluationController::class, 'submit']);
    });
});
