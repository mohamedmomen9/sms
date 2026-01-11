<?php

use Illuminate\Support\Facades\Route;
use Modules\Teachers\Http\Controllers\TeachersController;

Route::middleware(['web', 'auth'])->prefix('teachers')->name('teachers.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [TeachersController::class, 'index'])->name('index');
});
