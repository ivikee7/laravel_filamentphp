<?php

namespace App\Models;

use App\Models\AcadamicSession;
use App\Models\AdmissionClass;
use App\Models\AdmissionSection;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'class_id',
        'session_id',
        'store_id',
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

    function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updater_id');
    }
    function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    function class(): BelongsTo
    {
        return $this->belongsTo(AdmissionClass::class);
    }
    function session(): BelongsTo
    {
        return $this->belongsTo(AcadamicSession::class);
    }
}
