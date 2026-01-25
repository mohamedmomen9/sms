<?php

use Illuminate\Support\Facades\Route;
use Modules\System\Http\Controllers\SystemController;

Route::prefix('v1')->group(function () {
    Route::get('app-version', [SystemController::class, 'appVersion']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('agreement-status', [SystemController::class, 'agreementStatus']);
        Route::post('accept-agreement', [SystemController::class, 'acceptAgreement']);
    });
});
