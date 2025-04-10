<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bus_id',
        'date',
        'liters',
        'cost',
        'filled_by',
        'creator_id',
        'updater_id',
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

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }
}
