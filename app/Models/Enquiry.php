<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Enquiry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'gender_id',
        'date_of_birth',
        'father_name',
        'mother_name',
        'primary_contact_number',
        'secondary_contact_number',
        'email',
        'address',
        'city',
        'state',
        'pin_code',
        'previous_school',
        'source',
        'previous_class_id',
        'class_id',
        'notes',
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
}
