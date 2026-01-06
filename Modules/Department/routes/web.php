<?php

use Illuminate\Support\Facades\Route;
use Modules\Department\Http\Controllers\DepartmentController;

Route::middleware(['web', 'auth'])->prefix('department')->name('department.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [DepartmentController::class, 'index'])->name('index');
});
