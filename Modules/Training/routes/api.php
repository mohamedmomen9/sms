<?php

use Illuminate\Support\Facades\Route;
use Modules\Training\Http\Controllers\FieldTrainingController;

Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::prefix('training')->group(function () {
        Route::get('opportunities', [FieldTrainingController::class, 'opportunities']);
        Route::post('apply', [FieldTrainingController::class, 'apply']);
        Route::get('wishlist', [FieldTrainingController::class, 'getWishlist']);
        Route::post('wishlist', [FieldTrainingController::class, 'submitWishlist']);
    });
});
