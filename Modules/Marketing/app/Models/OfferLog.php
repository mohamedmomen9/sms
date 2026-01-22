<?php

namespace Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

class OfferLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'user_id',
        'action', // viewed, used
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
