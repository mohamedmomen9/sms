<?php

namespace Modules\Family\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Family\Database\Factories\GuardianFactory;

class Guardian extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'national_id',
        'job',
        'address',
        'is_active',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function students()
    {
        return $this->belongsToMany(\Modules\Students\Models\Student::class, 'parent_student', 'parent_id', 'student_id')
            ->withPivot('relationship_type');
    }



    // protected static function newFactory(): GuardianFactory
    // {
    //     // return GuardianFactory::new();
    // }
}
