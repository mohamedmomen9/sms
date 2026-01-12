<?php

use Illuminate\Support\Facades\Route;
use Modules\Teachers\Http\Controllers\AuthController;
use Modules\Teachers\Http\Controllers\TeachersController;

Route::middleware(['api'])->prefix('api/teachers')->name('api.teachers.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('teachers', TeachersController::class);
});

Route::middleware(['api'])->prefix('auth/teacher')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
});
