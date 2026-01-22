<?php

namespace Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'discount_code',
        'valid_from',
        'valid_until',
        'type', // internal, external
        'provider_name',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function logs()
    {
        return $this->hasMany(OfferLog::class);
    }
}
