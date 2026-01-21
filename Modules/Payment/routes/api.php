<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

Route::middleware(['api'])->prefix('api/payment')->name('api.payment.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('payments', PaymentController::class);
});
