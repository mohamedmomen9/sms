<?php

use Illuminate\Support\Facades\Route;
use Modules\Training\Http\Controllers\FieldTrainingController;
use Modules\Auth\Http\Middleware\UniversalJwtMiddleware;

Route::prefix('v1')->middleware([UniversalJwtMiddleware::class])->group(function () {
    Route::prefix('training')->group(function () {
        Route::get('opportunities', [FieldTrainingController::class, 'opportunities']);
        Route::post('apply', [FieldTrainingController::class, 'apply']);
        Route::get('wishlist', [FieldTrainingController::class, 'getWishlist']);
        Route::post('wishlist', [FieldTrainingController::class, 'submitWishlist']);
    });
});
