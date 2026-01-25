<?php

use Illuminate\Support\Facades\Route;
use Modules\Marketing\Http\Controllers\OfferController;

Route::prefix('v1')->group(function () {
    Route::get('offers', [OfferController::class, 'index']);
    Route::get('offers/favorites', [OfferController::class, 'favorites']);
    Route::get('offers/{id}', [OfferController::class, 'show']);
    Route::post('offers/{id}/like', [OfferController::class, 'toggleLike']);
});
