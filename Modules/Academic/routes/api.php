<?php

use Illuminate\Support\Facades\Route;
use Modules\Academic\Http\Controllers\AcademicController;

Route::prefix('academic')->middleware('api')->group(function () {
    Route::get('current', [AcademicController::class, 'current']);
});
