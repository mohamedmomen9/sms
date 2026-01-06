<?php

use Illuminate\Support\Facades\Route;
use Modules\Campus\Http\Controllers\CampusController;

Route::middleware(['api'])->prefix('api/campus')->name('api.campus.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('campuses', CampusController::class);
});
