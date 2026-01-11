<?php

use Illuminate\Support\Facades\Route;
use Modules\Students\Http\Controllers\StudentsController;

Route::middleware(['web', 'auth'])->prefix('students')->name('students.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [StudentsController::class, 'index'])->name('index');
});
