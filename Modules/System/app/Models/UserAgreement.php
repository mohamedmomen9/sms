<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreeable_type',
        'agreeable_id',
        'agreement_type',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function agreeable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function hasAccepted(Model $user, string $type): bool
    {
        return static::where('agreeable_type', $user->getMorphClass())
            ->where('agreeable_id', $user->getKey())
            ->where('agreement_type', $type)
            ->exists();
    }

    public static function accept(Model $user, string $type): self
    {
        return static::firstOrCreate(
            [
                'agreeable_type' => $user->getMorphClass(),
                'agreeable_id' => $user->getKey(),
                'agreement_type' => $type,
            ],
            [
                'accepted_at' => now(),
            ]
        );
    }
}
