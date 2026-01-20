<?php

use Illuminate\Support\Facades\Route;
use Modules\Training\Http\Controllers\TrainingController;

Route::middleware(['web', 'auth'])->prefix('training')->name('training.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [TrainingController::class, 'index'])->name('index');
});
