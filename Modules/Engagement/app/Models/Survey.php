<?php

namespace Modules\Engagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'target_audience', // all, campus, faculty, department, course
        'target_id',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function logs()
    {
        return $this->hasMany(SurveyLog::class);
    }
}
