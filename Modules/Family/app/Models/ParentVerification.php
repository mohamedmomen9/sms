<?php

namespace Modules\Family\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'phone',
        'otp',
        'otp_expires_at',
        'verified_at',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class, 'parent_id');
    }

    public static function clearOtp(int $parentId): void
    {
        static::where('parent_id', $parentId)->update([
            'otp' => null,
            'otp_expires_at' => null
        ]);
    }
}
