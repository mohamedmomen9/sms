<?php

use Illuminate\Support\Facades\Route;
use Modules\Department\Http\Controllers\DepartmentController;

Route::middleware(['api'])->prefix('api/department')->name('api.department.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('departments', DepartmentController::class);
});
