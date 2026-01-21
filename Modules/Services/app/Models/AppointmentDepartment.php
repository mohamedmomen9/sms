<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentDepartment extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Modules\Services\Database\Factories\AppointmentDepartmentFactory::new();
    }

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'working_hours',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'working_hours' => 'array',
    ];

    public function purposes(): HasMany
    {
        return $this->hasMany(AppointmentPurpose::class, 'department_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'department_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
