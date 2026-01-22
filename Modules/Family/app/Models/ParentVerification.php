<?php

namespace Modules\Family\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

class ParentVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'parents_id',
        'national_id_image',
        'status', // pending, approved, rejected
        'admin_id',
        'notes',
    ];

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'parents_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
