<?php

namespace App\Models;

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
        'primary_contact_number',
        'secondary_contact_number',
        'address',
        'city',
        'state',
        'pin_code',
        'previous_school',
        'source',
        'previous_class_id',
        'class_id',
        'creator_id',
        'updater_id',
    ];

    protected $dates = ['deleted_at'];

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


    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
    public function previousClass(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
}
