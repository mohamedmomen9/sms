<?php

use Illuminate\Support\Facades\Route;
use Modules\Services\Http\Controllers\AppointmentController;
use Modules\Services\Http\Controllers\ServiceRequestController;
use Modules\Auth\Http\Middleware\UniversalJwtMiddleware;

Route::prefix('v1')->middleware([UniversalJwtMiddleware::class])->group(function () {
    // Appointments
    Route::prefix('appointments')->group(function () {
        Route::get('departments', [AppointmentController::class, 'departments']);
        Route::get('slots', [AppointmentController::class, 'availableSlots']);
        Route::get('my', [AppointmentController::class, 'myAppointments']);
        Route::post('book', [AppointmentController::class, 'book']);
        Route::delete('{id}', [AppointmentController::class, 'cancel']);
    });

    // Services
    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceRequestController::class, 'available']);
        Route::get('my', [ServiceRequestController::class, 'myRequests']);
        Route::post('request', [ServiceRequestController::class, 'submit']);
    });
});
