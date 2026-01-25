<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'body',
        'extra_data',
    ];

    protected $casts = [
        'extra_data' => 'array',
    ];

    /**
     * Get all logs for this notification
     */
    public function logs()
    {
        return $this->hasMany(NotificationLog::class);
    }

    /**
     * Scope to search notifications by title
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('body', 'like', "%{$term}%");
        });
    }

    /**
     * Scope to order by most recent first
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Get count of unread logs for this notification
     */
    public function getUnreadCountAttribute(): int
    {
        return $this->logs()->where('is_read', false)->count();
    }

    /**
     * Get count of read logs for this notification  
     */
    public function getReadCountAttribute(): int
    {
        return $this->logs()->where('is_read', true)->count();
    }
}
