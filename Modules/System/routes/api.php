<?php

use Illuminate\Support\Facades\Route;
use Modules\System\Http\Controllers\SystemController;

Route::prefix('v1')->group(function () {
    Route::get('app-versions/latest', [\Modules\System\Http\Controllers\AppVersionController::class, 'latest']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('agreement-status', [SystemController::class, 'agreementStatus']);
        Route::post('accept-agreement', [SystemController::class, 'acceptAgreement']);

        // Alternative endpoints
        Route::get('user-agreements/status', [\Modules\System\Http\Controllers\UserAgreementController::class, 'status']);
        Route::post('user-agreements/accept', [\Modules\System\Http\Controllers\UserAgreementController::class, 'accept']);
    });
});
