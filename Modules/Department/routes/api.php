<?php

use Illuminate\Support\Facades\Route;
use Modules\Department\Http\Controllers\DepartmentController;

Route::prefix('v1')->middleware(['api'])->group(function () {
    
    Route::prefix('department')->name('api.department.')->group(function () {
        // Define your module API routes here
        // Example:
        // Route::apiResource('departments', DepartmentController::class);
    });
});
