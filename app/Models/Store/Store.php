<?php

namespace App\Models\Store;

use App\Models\School\Admission;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'contact',
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
    function products(): HasMany
    {
        return $this->hasMany(StoreProduct::class);
    }
    function students(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function student() : array
    {
        return User::get();
    }
}
