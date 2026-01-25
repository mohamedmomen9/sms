<?php

namespace Modules\Communications\Models;

use Modules\Campus\Models\Campus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Communications\Database\Factories\AnnouncementFactory;

class Announcement extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return AnnouncementFactory::new();
    }

    protected $fillable = [
        'title',
        'details',
        'campus_id',
        'link',
        'date',
        'type',
        'is_active',
        'image',
        'cropped_image',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    /**
     * Scope to filter by campus (null = all campuses)
     */
    public function scopeForCampus(Builder $query, ?int $campusId): Builder
    {
        if ($campusId === null) {
            return $query;
        }

        return $query->where(function ($q) use ($campusId) {
            $q->where('campus_id', $campusId)
                ->orWhereNull('campus_id');
        });
    }

    /**
     * Scope to filter by single type
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by multiple types
     */
    public function scopeOfTypes(Builder $query, array $types): Builder
    {
        return $query->whereIn('type', $types);
    }

    /**
     * Scope for active announcements only
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to search announcements by title or details
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('details', 'like', "%{$term}%");
        });
    }

    /**
     * Scope to order by most recent first
     */
    public function scopeLatest(Builder $query, string $column = 'date'): Builder
    {
        return $query->orderByDesc($column);
    }

    /**
     * Check if announcement is for all campuses
     */
    public function isForAllCampuses(): bool
    {
        return $this->campus_id === null;
    }

    /**
     * Get available announcement types
     */
    public static function getTypes(): array
    {
        return ['news', 'events', 'lectures', 'announcements'];
    }
}
