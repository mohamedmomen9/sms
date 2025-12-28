<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campus extends Model
{
    protected $fillable = [
        'code',
        'name',
        'name_en',
        'name_ar',
        'location',
        'address',
        'phone',
        'email',
        'status',
    ];

    /**
     * Get all faculties in this campus
     */
    public function faculties(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }



    /**
     * Check if campus is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
