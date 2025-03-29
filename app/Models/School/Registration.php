<?php

namespace App\Models\School;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'date_of_birth',
        'gender',
        'father_name',
        'father_qualification',
        'father_occupation',
        'father_contact_number',
        'mother_name',
        'mother_qualification',
        'mother_occupation',
        'mother_contact_number',
        'address',
        'city',
        'state',
        'pin_code',
        'class_id',
        'last_attended_school',
        'last_attended_class_id',
        'payment_mode',
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

    function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updater_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(AdmissionClass::class, 'class_id', 'id');
    }

    public function lastAttendedClass(): BelongsTo
    {
        return $this->belongsTo(AdmissionClass::class, 'class_id', 'id');
    }
}
