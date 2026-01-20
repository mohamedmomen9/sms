<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppointmentSlot extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
        'label',
        'is_available',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_available' => 'boolean',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'slot_id');
    }

    public function getLabelAttribute($value): string
    {
        if ($value) {
            return $value;
        }
        return $this->start_time?->format('g:i A') . ' - ' . $this->end_time?->format('g:i A');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}
