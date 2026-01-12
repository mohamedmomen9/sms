<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::prefix('v1')->middleware('api')->group(function () {
    
    Route::prefix('auth')->group(function () {
        // Generic Login
        // POST /api/v1/auth/login (Body must contain "role": "student" or "teacher")
        Route::post('login', [AuthController::class, 'login']);

        // Role-specific Routes (Legacy support / Cleaner URL)
        // POST /api/v1/auth/student/login
        Route::post('{role}/login', [AuthController::class, 'login'])->where('role', 'student|teacher|staff');

        // Generic Refresh/Logout
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout']);
        
        // Scoped Refresh/Logout (optional, routing to same logic)
        Route::post('{role}/refresh', [AuthController::class, 'refresh']);
        Route::post('{role}/logout', [AuthController::class, 'logout']);
    });
});
