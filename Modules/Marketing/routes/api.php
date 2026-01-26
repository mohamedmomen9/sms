<?php

use Illuminate\Support\Facades\Route;
use Modules\Marketing\Http\Controllers\OfferController;

use Modules\Marketing\Http\Middleware\InjectMarketingContext;

Route::prefix('v1')->middleware(InjectMarketingContext::class)->group(function () {
    Route::get('offers', [OfferController::class, 'index']);
    Route::get('offers/favorites', [OfferController::class, 'favorites']);
    Route::get('offers/{id}', [OfferController::class, 'show']);
    Route::post('offers/{id}/like', [OfferController::class, 'toggleLike']);
});
