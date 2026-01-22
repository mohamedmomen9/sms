<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_number',
        'platform', // ios, android
        'is_mandatory',
        'release_date',
        'description',
        'download_url',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'release_date' => 'date',
    ];
}
