<?php

namespace Modules\Engagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'participant_type',
        'participant_id',
        'status', // Is completed
        'completed_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }
}
