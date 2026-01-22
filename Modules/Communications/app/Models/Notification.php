<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'target_audience', // all, campus, faculty, department, specific_users
        'target_id',       // ID of target entity
        'title',
        'body',
        'action_url',
        'author_id',
        'schedule_at',
        'status',          // draft, scheduled, sent, cancelled
    ];

    protected $casts = [
        'schedule_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function logs()
    {
        return $this->hasMany(NotificationLog::class);
    }
}
