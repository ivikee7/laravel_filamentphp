<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcadamicSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'creator_id',
        'updater_id',
        'is_active',
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

    protected static function booted()
    {
        static::updating(function ($model) {
            if ($model->is_active) {
                static::where('id', '!=', $model->id)->update(['is_active' => false]);
            }
        });
        static::creating(function ($model) {
            if ($model->is_active) {
                static::where('id', '!=', $model->id)->update(['is_active' => false]);
            }
        });
    }

    // relationship
    function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updater_id');
    }
    function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }
}
