<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Registration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'date_of_birth',
        'gender_id',
        'father_name',
        'father_qualification',
        'father_occupation',
        'primary_contact_number',
        'mother_name',
        'mother_qualification',
        'mother_occupation',
        'secondary_contact_number',
        'email',
        'address',
        'city',
        'state',
        'pin_code',
        'previous_school',
        'payment_mode',
        'payment_amount',
        'payment_notes',
        'previous_class_id',
        'academic_year_id',
        'class_id',
        'creator_id',
        'updater_id',
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
        static::deleting(function ($model) {
            if (Auth::check()) {
                $model->deleted_by = Auth::id();
                $model->saveQuietly();
            }
        });
        static::restoring(function ($model) {
            $model->deleted_by = null;
            $model->saveQuietly();
        });
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }



    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
    public function previousClass(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }
}
