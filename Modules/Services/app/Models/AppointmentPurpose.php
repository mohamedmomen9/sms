<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentPurpose extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(AppointmentDepartment::class, 'department_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
