<?php

use Illuminate\Support\Facades\Route;
use Modules\Academic\Http\Controllers\AcademicController;

Route::prefix('api/academic')->middleware('api')->group(function () {
    Route::get('current', [AcademicController::class, 'current']);
});
