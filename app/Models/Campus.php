<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Campus extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'code',
        'name',
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
