<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    protected $fillable = [
        'campus_id',
        'code',
        'name',
        'name_en',
        'name_ar',
    ];

    /**
     * Get the campus this faculty belongs to (optional)
     */
    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }


}
