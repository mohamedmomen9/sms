<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::middleware(['web', 'auth'])->prefix('auth')->name('auth.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [AuthController::class, 'index'])->name('index');
});
