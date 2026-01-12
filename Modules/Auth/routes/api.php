<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::middleware('api')->prefix('auth')->group(function () {
    
    // Generic Login
    // POST /api/auth/login (Body must contain "role": "student" or "teacher")
    Route::post('login', [AuthController::class, 'login']);

    // Role-specific Routes (Legacy support / Cleaner URL)
    // POST /api/auth/student/login
    Route::post('{role}/login', [AuthController::class, 'login'])->where('role', 'student|teacher|staff');

    // Generic Refresh/Logout
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
    
    // Scoped Refresh/Logout (optional, routing to same logic)
    Route::post('{role}/refresh', [AuthController::class, 'refresh']);
    Route::post('{role}/logout', [AuthController::class, 'logout']);
});
