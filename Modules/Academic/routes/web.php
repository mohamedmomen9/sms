<?php

use Illuminate\Support\Facades\Route;
use Modules\Academic\Http\Controllers\AcademicController;

Route::middleware(['web', 'auth'])->prefix('academic')->name('academic.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [AcademicController::class, 'index'])->name('index');
});
