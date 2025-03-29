<?php

namespace App\Models\School;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'class_id',
        'section_id',
        'acadamic_session_id',
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

    // relationship
    function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updater_id');
    }
    function session(): BelongsTo
    {
        return $this->belongsTo(AcadamicSession::class, 'acadamic_session_id');
    }
    function class(): BelongsTo
    {
        return $this->belongsTo(AdmissionClass::class, 'class_id');
    }
    function section(): BelongsTo
    {
        return $this->belongsTo(AdmissionSection::class, 'section_id');
    }
    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
