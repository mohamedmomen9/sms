<?php

namespace Modules\Engagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

class SurveyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'user_id',
        'status', // pending, completed, skipped
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
