<?php

use Illuminate\Support\Facades\Route;
use Modules\Campus\Http\Controllers\CampusController;

Route::prefix('v1')->middleware(['api'])->group(function () {
    
    Route::prefix('campus')->name('api.campus.')->group(function () {
        // Define your module API routes here
        // Example:
        // Route::apiResource('campuses', CampusController::class);
    });
});
