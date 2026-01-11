<?php

namespace Modules\Campus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_facilities');
    }
}
