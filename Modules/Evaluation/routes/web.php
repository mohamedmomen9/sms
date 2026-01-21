<?php

use Illuminate\Support\Facades\Route;
use Modules\Evaluation\Http\Controllers\EvaluationController;

Route::middleware(['web', 'auth'])->prefix('evaluation')->name('evaluation.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [EvaluationController::class, 'index'])->name('index');
});
