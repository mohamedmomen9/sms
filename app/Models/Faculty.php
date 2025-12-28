<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Faculty extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'campus_id',
        'code',
        'name',
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
