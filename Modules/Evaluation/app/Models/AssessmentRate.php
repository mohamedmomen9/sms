<?php

namespace Modules\Evaluation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentRate extends Model
{
    protected $fillable = [
        'assessment_id',
        'name',
        'weight',
        'sort_order',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
}
