<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'details',
        'campus_id',
        'link',
        'date',
        'type',
        'is_active',
        'image',
        'cropped_image',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function campus()
    {
        return $this->belongsTo(\Modules\Campus\Models\Campus::class);
    }
}
