<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;

Route::middleware(['api'])->prefix('api/users')->name('api.users.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('users', UsersController::class);
});
