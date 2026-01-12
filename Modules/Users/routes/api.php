<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;

Route::prefix('v1')->middleware(['api'])->group(function () {
    
    Route::prefix('users')->name('api.users.')->group(function () {
        // Define your module API routes here
        // Example:
        // Route::apiResource('users', UsersController::class);
    });
});
