<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class LookupItem extends Model
{
    use HasTranslations, HasFactory;

    protected $fillable = [
        'type',
        'code',
        'name',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public $translatable = ['name'];
}
