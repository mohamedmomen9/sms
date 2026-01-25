<?php

namespace Modules\Students\Models;

use Laravel\Sanctum\HasApiTokens;
use Modules\Campus\Models\Campus;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;
use Illuminate\Notifications\Notifiable;
use Modules\Department\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Communications\Traits\HasNotificationLogs;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Students\Database\Factories\StudentFactory;
use Modules\Family\Models\Guardian;
use Modules\Marketing\Models\OfferLog;
use Modules\Marketing\Models\Offer;
use Modules\Students\Models\CourseEnrollment;
use Modules\Engagement\Models\SurveyLog;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Collection;

class Student extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasNotificationLogs;

    protected static function newFactory()
    {
        return StudentFactory::new();
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'student_id',
        'date_of_birth',
        'campus_id',
        'school_id',
        'department_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function school()
    {
        return $this->belongsTo(Faculty::class, 'school_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subjects()
    {
        return Subject::whereHas('offerings.enrollments', function ($query) {
            $query->where('student_id', $this->id);
        });
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function currentClasses()
    {
        return $this->enrollments()->whereHas('courseOffering.term', function ($query) {
            $query->where('is_active', true);
        });
    }

    // --- New Relations ---

    public function guardians(): HasMany
    {
        return $this->hasMany(Guardian::class, 'student_id');
    }

    public function image(): HasOne
    {
        return $this->hasOne(StudentImage::class);
    }

    public function offerLogs(): HasMany
    {
        return $this->hasMany(OfferLog::class, 'entity_id')
            ->where('entity_type', 'student');
    }

    public function surveyLogs(): HasMany
    {
        return $this->hasMany(SurveyLog::class, 'participant_id')
            ->where('participant_type', 'STUDENT');
    }

    // --- New Methods ---

    public function favoriteOffers(): Collection
    {
        return Offer::whereHas('logs', function ($query) {
            $query->where('entity_type', 'student')
                ->where('entity_id', $this->id)
                ->where('is_favorite', true);
        })->get();
    }

    public function getProfileImage(): ?StudentImage
    {
        return $this->image;
    }
}
