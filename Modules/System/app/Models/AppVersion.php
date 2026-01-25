<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'version',
        'min_version',
        'force_update',
        'release_notes',
    ];

    protected $casts = [
        'force_update' => 'boolean',
    ];
}
