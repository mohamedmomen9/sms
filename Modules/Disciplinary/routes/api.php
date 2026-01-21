<?php

use Illuminate\Support\Facades\Route;
use Modules\Disciplinary\Http\Controllers\GrievanceController;

Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::prefix('grievances')->group(function () {
        Route::get('/', [GrievanceController::class, 'index']);
        Route::post('{id}/appeal', [GrievanceController::class, 'submitAppeal']);
    });
});
