<?php

namespace Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Campus\Models\Campus;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'details',
        'campus_id',
        'link',
        'date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'date' => 'date',
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(OfferLog::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForCampus(Builder $query, ?int $campusId = null): Builder
    {
        if (is_null($campusId)) {
            return $query;
        }

        return $query->where(function ($q) use ($campusId) {
            $q->whereNull('campus_id')
                ->orWhere('campus_id', $campusId);
        });
    }

    public function isFavoritedBy(Model $entity): bool
    {
        // Check manually using entity type/id since table uses separate columns
        $type = $this->mapModelToEntityType($entity);
        if (! $type) {
            return false;
        }

        return $this->logs()
            ->where('entity_type', $type)
            ->where('entity_id', $entity->id)
            ->where('is_favorite', true)
            ->exists();
    }

    public function mapModelToEntityType(Model $entity): ?string
    {
        // Map class name to simple entity type string
        $class = get_class($entity);
        if (str_contains($class, 'Student')) {
            return 'student';
        }
        if (str_contains($class, 'Teacher')) {
            return 'teacher';
        }
        if (str_contains($class, 'Guardian')) {
            return 'parent';
        }

        return null;
    }
}
