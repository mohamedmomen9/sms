<?php

use Illuminate\Support\Facades\Route;
use Modules\Disciplinary\Http\Controllers\DisciplinaryController;

Route::middleware(['api'])->prefix('api/disciplinary')->name('api.disciplinary.')->group(function () {
    // Define your module API routes here
    // Example:
    // Route::apiResource('disciplinaries', DisciplinaryController::class);
});
