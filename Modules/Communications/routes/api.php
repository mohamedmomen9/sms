<?php

use Illuminate\Support\Facades\Route;
use Modules\Communications\Http\Controllers\AnnouncementController;
use Modules\Communications\Http\Controllers\NotificationController;

use Modules\Communications\Http\Middleware\InjectAnnouncementContext;

Route::prefix('v1')->middleware(InjectAnnouncementContext::class)->group(function () {
    // Announcements
    Route::get('announcements', [AnnouncementController::class, 'index']);
    Route::get('announcements/{id}', [AnnouncementController::class, 'show']);
    Route::get('search/announcements', [AnnouncementController::class, 'search']);

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::get('notifications/{id}', [NotificationController::class, 'show']);
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // Instructor Notifications
    Route::get('instructor/notifications', [NotificationController::class, 'instructorIndex']);
});
