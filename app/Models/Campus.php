<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campus extends Model
{
    protected $fillable = [
        'university_id',
        'code',
        'name',
        'location',
        'address',
        'phone',
        'email',
        'status',
    ];

    /**
     * Get the university this campus belongs to
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get all faculties in this campus
     */
    public function faculties(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }

    /**
     * Get departments count through faculties
     */
    public function getDepartmentsCountAttribute(): int
    {
        return Department::whereIn('faculty_id', $this->faculties()->pluck('id'))->count();
    }

    /**
     * Check if campus is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
