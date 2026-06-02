<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSettings extends Model
{
    protected $table = 'website_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function getValueAttribute($value)
    {
        return match ($this->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => $value ? json_decode($value, true) : null,
            'integer' => $value !== null ? (int) $value : null,
            default => $value,
        };
    }

    public function setValueAttribute($value): void
    {
        if ($this->type === 'json') {
            $this->attributes['value'] = $value === null ? null : json_encode($value);

            return;
        }

        $this->attributes['value'] = $value;
    }

    public static function getValueByKey(string $key, mixed $default = null): mixed
    {
        $setting = static::query()->where('key', $key)->first();

        return $setting?->value ?? $default;
    }
}
