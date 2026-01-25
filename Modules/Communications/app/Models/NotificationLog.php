<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_id',
        'notifiable_type',
        'notifiable_id',
        'title',
        'subtitle',
        'body',
        'topic',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get the parent notification
     */
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    /**
     * Get the notifiable entity (Student, Teacher, User, etc.)
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for unread notifications only
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications only
     */
    public function scopeRead(Builder $query): Builder
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope to filter by notifiable type
     */
    public function scopeForNotifiableType(Builder $query, string $type): Builder
    {
        return $query->where('notifiable_type', $type);
    }

    /**
     * Scope to filter by notifiable
     */
    public function scopeForNotifiable(Builder $query, string $type, int $id): Builder
    {
        return $query->where('notifiable_type', $type)
            ->where('notifiable_id', $id);
    }

    /**
     * Mark this log as read
     */
    public function markAsRead(): bool
    {
        return $this->update(['is_read' => true]);
    }

    /**
     * Mark this log as unread
     */
    public function markAsUnread(): bool
    {
        return $this->update(['is_read' => false]);
    }
}
