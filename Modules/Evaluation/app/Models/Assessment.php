<?php

namespace Modules\Evaluation\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Evaluation\Models\Evaluation;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    protected $fillable = [
        'name',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(AssessmentCategory::class)->orderBy('sort_order');
    }

    public function rates(): HasMany
    {
        return $this->hasMany(AssessmentRate::class)->orderBy('sort_order');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
