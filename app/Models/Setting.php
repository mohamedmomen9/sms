<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($setting->value, true),
            'integer' => (int) $setting->value,
            default => $setting->value,
        };
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, mixed $value, string $type = 'string', string $group = 'general'): static
    {
        $storedValue = match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            default => (string) $value,
        };

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $storedValue, 'type' => $type, 'group' => $group]
        );
    }

    /**
     * Get all settings as key-value array
     */
    public static function allGrouped(): array
    {
        return static::all()->groupBy('group')->map(function ($items) {
            return $items->pluck('value', 'key');
        })->toArray();
    }
}
