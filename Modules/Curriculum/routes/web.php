<?php

use Illuminate\Support\Facades\Route;
use Modules\Curriculum\Http\Controllers\CurriculumController;

Route::middleware(['web', 'auth'])->prefix('curriculum')->name('curriculum.')->group(function () {
    // Define your module routes here
    // Example:
    // Route::get('/', [CurriculumController::class, 'index'])->name('index');
});
