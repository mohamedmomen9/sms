<?php

use Illuminate\Support\Facades\Route;
use Modules\Campus\Http\Controllers\CampusController;

Route::middleware(['web', 'auth'])->prefix('campus')->name('campus.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [CampusController::class, 'index'])->name('index');
});
