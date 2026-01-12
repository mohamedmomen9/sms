<?php

use Illuminate\Support\Facades\Route;
use Modules\Academic\Http\Controllers\AcademicController;
use Modules\Academic\Http\Controllers\ScheduleController;
use Modules\Auth\Http\Middleware\UniversalJwtMiddleware;

Route::prefix('v1')->middleware('api')->group(function () {

    // Academic routes
    Route::prefix('academic')->group(function () {
        Route::get('current', [AcademicController::class, 'current']);
    });

    // Student Schedule Routes
    Route::prefix('student/schedule')->middleware(UniversalJwtMiddleware::class)->group(function () {
        Route::get('/', [ScheduleController::class, 'studentSchedule'])->name('api.student.schedule');
        Route::get('/today', [ScheduleController::class, 'studentTodaySchedule'])->name('api.student.schedule.today');
    });

    // Teacher Schedule Routes
    Route::prefix('teacher/schedule')->middleware(UniversalJwtMiddleware::class)->group(function () {
        Route::get('/', [ScheduleController::class, 'teacherSchedule'])->name('api.teacher.schedule');
        Route::get('/today', [ScheduleController::class, 'teacherTodaySchedule'])->name('api.teacher.schedule.today');
    });
});
