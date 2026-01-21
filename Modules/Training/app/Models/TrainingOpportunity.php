<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Faculty\Models\Faculty;
use Modules\Department\Models\Department;

class TrainingOpportunity extends Model
{
    protected $fillable = [
        'organization_name',
        'description',
        'faculty_id',
        'department_id',
        'concentration',
        'cohort',
        'capacity',
        'start_date',
        'end_date',
        'is_available',
        'conditions',
        'required_documents',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_available' => 'boolean',
        'conditions' => 'array',
        'required_documents' => 'array',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(FieldTraining::class, 'opportunity_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}
