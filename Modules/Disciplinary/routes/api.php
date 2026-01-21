<?php

use Illuminate\Support\Facades\Route;
use Modules\Disciplinary\Http\Controllers\GrievanceController;
use Modules\Auth\Http\Middleware\UniversalJwtMiddleware;

Route::prefix('v1')->middleware([UniversalJwtMiddleware::class])->group(function () {
    Route::prefix('grievances')->group(function () {
        Route::get('/', [GrievanceController::class, 'index']);
        Route::post('{id}/appeal', [GrievanceController::class, 'submitAppeal']);
    });
});
