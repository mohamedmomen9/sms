<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class UserAgreement extends Model
{
    use HasTranslations, HasFactory;

    protected $fillable = [
        'type', // privacy_policy, terms
        'version',
        'content',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public $translatable = ['content'];
}
