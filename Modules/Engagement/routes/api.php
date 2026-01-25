<?php

use Illuminate\Support\Facades\Route;
use Modules\Engagement\Http\Controllers\SurveyController;

Route::prefix('v1')->group(function () {
    Route::get('surveys', [SurveyController::class, 'index']);
    Route::get('surveys/{id}', [SurveyController::class, 'show']);
});
