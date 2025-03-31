<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'gender',
        'date_of_birth',
        'father_name',
        'mother_name',
        'father_contact_name',
        'mother_contact_name',
        'class_id',
        'message',
        'address',
        'city',
        'state',
        'pin_code',
        'last_attended_school',
        'last_attended_class_id',
        'source',
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
}
