<?php

namespace Modules\Subject\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionType extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Modules\Subject\Database\Factories\SessionTypeFactory::new();
    }

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Available session type codes for reference
     */
    public const CODES = [
        'C' => 'Class',
        'LAB' => 'Laboratory',
        'LECT' => 'Lecture',
        'PR' => 'Practical',
        'TUT' => 'Tutorial',
    ];

    /**
     * Get all schedules using this session type
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(CourseSchedule::class);
    }

    /**
     * Get display label (code + name)
     */
    public function getLabelAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }

    /**
     * Scope to only active session types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
