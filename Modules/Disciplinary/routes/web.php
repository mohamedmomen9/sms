<?php

use Illuminate\Support\Facades\Route;
use Modules\Disciplinary\Http\Controllers\DisciplinaryController;

Route::middleware(['web', 'auth'])->prefix('disciplinary')->name('disciplinary.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [DisciplinaryController::class, 'index'])->name('index');
});
