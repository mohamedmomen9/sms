<?php

use Illuminate\Support\Facades\Route;
use Modules\System\Http\Middleware\InjectUserContext;

use Modules\Marketing\Http\Controllers\OfferController;
use Modules\Auth\Http\Middleware\UniversalJwtMiddleware;
use Modules\Marketing\Http\Middleware\InjectMarketingContext;

Route::prefix('v1')->middleware([UniversalJwtMiddleware::class, InjectMarketingContext::class, InjectUserContext::class])->group(function () {
    Route::get('offers', [OfferController::class, 'index']);
    Route::get('offers/favorites', [OfferController::class, 'favorites']);
    Route::get('offers/{id}', [OfferController::class, 'show']);
    Route::post('offers/{id}/like', [OfferController::class, 'toggleLike']);
});
