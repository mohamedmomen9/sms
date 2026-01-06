<?php

use Illuminate\Support\Facades\Route;
use Modules\People\Http\Controllers\PeopleController;

Route::middleware(['web', 'auth'])->prefix('people')->name('people.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [PeopleController::class, 'index'])->name('index');
});
