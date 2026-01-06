<?php

use Illuminate\Support\Facades\Route;
use Modules\People\Http\Controllers\PeopleController;

Route::middleware(['api'])->prefix('api/people')->name('api.people.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('people', PeopleController::class);
});
