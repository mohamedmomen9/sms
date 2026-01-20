<?php

use Illuminate\Support\Facades\Route;
use Modules\Services\Http\Controllers\ServicesController;

Route::middleware(['web', 'auth'])->prefix('services')->name('services.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [ServicesController::class, 'index'])->name('index');
});
