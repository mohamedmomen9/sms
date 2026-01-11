<?php

namespace Modules\Campus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Department\Models\Department;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'room_code',
        'number',
        'name',
        'floor_number',
        'type',
        'capacity',
        'status',
        'department_id',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'room_facilities');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Computed label: BUILDING CODE | Floor X | Room Y | Name
    public function getLabelNameAttribute(): string
    {
        $parts = [
            $this->building?->code ?? 'Unknown Building',
            "Floor {$this->floor_number}",
            "Room {$this->number}",
        ];

        if ($this->name) {
            $parts[] = $this->name;
        }

        return implode(' | ', $parts);
    }
}
