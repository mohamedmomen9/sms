<?php

use Illuminate\Support\Facades\Route;
use Modules\Subject\Http\Controllers\SubjectController;

Route::middleware(['web', 'auth'])->prefix('subject')->name('subject.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [SubjectController::class, 'index'])->name('index');
});
