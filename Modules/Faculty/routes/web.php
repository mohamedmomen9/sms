<?php

use Illuminate\Support\Facades\Route;
use Modules\Faculty\Http\Controllers\FacultyController;

Route::middleware(['web', 'auth'])->prefix('faculty')->name('faculty.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [FacultyController::class, 'index'])->name('index');
});
