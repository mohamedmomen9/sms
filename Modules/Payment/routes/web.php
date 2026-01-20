<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

Route::middleware(['web', 'auth'])->prefix('payment')->name('payment.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [PaymentController::class, 'index'])->name('index');
});
