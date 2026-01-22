<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_id',
        'user_id',
        'status', // sent, read, failed
        'read_at',
        'error_message',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
