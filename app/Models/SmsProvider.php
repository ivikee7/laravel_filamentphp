<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsProvider extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'base_url',
        'method',
        'params',
        'headers',
        'responses',
        'is_active',
        'to_key',
        'text_key',
        'creator_id',
        'updater_id',
    ];

    protected $casts = [
        'params' => 'array',
        'headers' => 'array',
        'responses' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->creator_id = auth()->id();
            }
        });
        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updater_id = auth()->id();
            }
        });
    }

    public static function getActiveProviders()
    {
        return self::where('is_active', true)->pluck('name', 'id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
