<?php

use Illuminate\Support\Facades\Route;
use Modules\Students\Http\Controllers\AuthController;
use Modules\Students\Http\Controllers\StudentsController;

Route::middleware(['api'])->prefix('api/students')->name('api.students.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('students', StudentsController::class);
});

Route::middleware(['api'])->prefix('auth/student')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
});
