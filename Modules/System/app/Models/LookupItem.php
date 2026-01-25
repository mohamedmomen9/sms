<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class LookupItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'code',
        'name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public static function getByType(string $type): Collection
    {
        return static::where('type', $type)->active()->ordered()->get();
    }

    public static function getOptions(string $type): array
    {
        return static::where('type', $type)
            ->active()
            ->ordered()
            ->pluck('name', 'code')
            ->toArray();
    }
}
