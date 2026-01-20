<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
        'duration_days',
        'requires_shipping',
        'is_mobile_visible',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'requires_shipping' => 'boolean',
        'is_mobile_visible' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function requests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'service_type_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)->where('is_mobile_visible', true);
    }
}
