<?php

namespace Modules\Engagement\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Campus\Models\Campus;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'active',
        'target_type', // ALL, STUDENT, TEACHER, PARENT
        'campus_id',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SurveyLog::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeForUser(Builder $query, Model $user): Builder
    {
        // Filter by target type and campus
        $type = $this->mapModelToTargetType($user);

        return $query->where('active', true)
            ->where(function ($q) use ($type) {
                $q->where('target_type', 'ALL')
                    ->orWhere('target_type', $type);
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('campus_id')
                    ->orWhere('campus_id', $user->campus_id ?? null);
            });
    }

    public function mapModelToTargetType(Model $user): string
    {
        $class = get_class($user);
        if (str_contains($class, 'Student')) return 'STUDENT';
        if (str_contains($class, 'Teacher')) return 'TEACHER';
        if (str_contains($class, 'Guardian')) return 'PARENT';
        return 'UNKNOWN';
    }
}
