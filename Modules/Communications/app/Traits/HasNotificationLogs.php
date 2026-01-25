<?php

namespace Modules\Communications\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Communications\Models\NotificationLog;

trait HasNotificationLogs
{
    /**
     * Get all notification logs for this entity
     */
    public function notificationLogs(): MorphMany
    {
        return $this->morphMany(NotificationLog::class, 'notifiable');
    }

    /**
     * Get unread notification logs
     */
    public function unreadNotificationLogs(): MorphMany
    {
        return $this->notificationLogs()->unread();
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->notificationLogs()->unread()->count();
    }

    /**
     * Get all notifications with their read status
     */
    public function getNotificationsWithStatus()
    {
        return $this->notificationLogs()
            ->with('notification')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Mark a specific notification as read
     */
    public function markNotificationAsRead(int $notificationId): bool
    {
        return $this->notificationLogs()
            ->where('notification_id', $notificationId)
            ->update(['is_read' => true]) > 0;
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead(): int
    {
        return $this->notificationLogs()
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
