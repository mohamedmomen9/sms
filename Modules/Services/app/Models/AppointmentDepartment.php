<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class AppointmentDepartment extends Model
{
    use HasTranslations, HasFactory;

    public $translatable = ['name'];

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

    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }
        return $attributes;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
