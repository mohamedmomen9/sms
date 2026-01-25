<?php

namespace Modules\Communications\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Communications\Models\Notification;
use Modules\Communications\Models\NotificationLog;

class NotificationService
{
    /**
     * Get all notifications
     */
    public function list(): Collection
    {
        return Notification::latest()->get();
    }

    /**
     * Create a new notification
     */
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    /**
     * Find a notification by ID
     */
    public function find(int $id): ?Notification
    {
        return Notification::find($id);
    }

    /**
     * Update a notification
     */
    public function update(int $id, array $data): ?Notification
    {
        $notification = $this->find($id);

        if (!$notification) {
            return null;
        }

        $notification->update($data);

        return $notification->fresh();
    }

    /**
     * Delete a notification
     */
    public function delete(int $id): bool
    {
        $notification = $this->find($id);

        if (!$notification) {
            return false;
        }

        return $notification->delete();
    }

    /**
     * Send notification to a single user
     */
    public function sendToUser(Notification $notification, Model $user): NotificationLog
    {
        return NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => get_class($user),
            'notifiable_id' => $user->id,
            'title' => $notification->title,
            'subtitle' => $notification->subtitle,
            'body' => $notification->body,
            'is_read' => false,
        ]);
    }

    /**
     * Send notification to multiple users
     * 
     * @return int Number of logs created
     */
    public function sendToMultiple(Notification $notification, Collection $users): int
    {
        $count = 0;

        foreach ($users as $user) {
            $this->sendToUser($notification, $user);
            $count++;
        }

        return $count;
    }

    /**
     * Mark a notification as read for a user
     */
    public function markAsRead(int $notificationId, Model $user): bool
    {
        return NotificationLog::where('notification_id', $notificationId)
            ->where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->update(['is_read' => true]) > 0;
    }

    /**
     * Get unread notifications for a user
     */
    public function getUnreadForUser(Model $user): Collection
    {
        return NotificationLog::with('notification')
            ->forNotifiable(get_class($user), $user->id)
            ->unread()
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get all notifications for a user (with read status)
     */
    public function getAllForUser(Model $user): Collection
    {
        return NotificationLog::with('notification')
            ->forNotifiable(get_class($user), $user->id)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get unread count for a user
     */
    public function getUnreadCount(Model $user): int
    {
        return NotificationLog::forNotifiable(get_class($user), $user->id)
            ->unread()
            ->count();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(Model $user): int
    {
        return NotificationLog::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Send notification to students by course
     * Note: This requires Students module integration
     */
    public function sendToStudentsByCourse(Notification $notification, int $courseOfferingId): int
    {
        // Import Student model dynamically to avoid hard dependency
        if (!class_exists('Modules\Students\Models\Student')) {
            return 0;
        }

        $students = \Modules\Students\Models\Student::whereHas('courseOfferings', function ($query) use ($courseOfferingId) {
            $query->where('course_offering_id', $courseOfferingId);
        })->get();

        return $this->sendToMultiple($notification, $students);
    }
}
