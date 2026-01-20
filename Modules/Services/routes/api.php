<?php

use Illuminate\Support\Facades\Route;
use Modules\Services\Http\Controllers\ServicesController;

Route::middleware(['api'])->prefix('api/services')->name('api.services.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('services', ServicesController::class);
});
