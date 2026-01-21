<?php

namespace Modules\Campus\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;

class Campus extends Model
{
    use HasTranslations, HasFactory;

    protected static function newFactory()
    {
        return \Modules\Campus\Database\Factories\CampusFactory::new();
    }

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
