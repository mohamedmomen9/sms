<?php

use Illuminate\Support\Facades\Route;
use Modules\Admissions\Http\Controllers\ApplicantController;

Route::prefix('v1')->group(function () {
    Route::post('applicants/login', [ApplicantController::class, 'login']);
});
