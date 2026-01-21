<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Academic\Database\Factories\AcademicYearFactory;

class AcademicYear extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Modules\Academic\Database\Factories\AcademicYearFactory::new();
    }

    protected $fillable = ['name', 'start_date', 'end_date', 'is_active', 'status'];

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    // protected static function newFactory(): AcademicYearFactory
    // {
    //     // return AcademicYearFactory::new();
    // }
}
