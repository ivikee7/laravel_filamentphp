<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StudentSection extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'student_sections';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'class_id',
        'student_class_id',
        'room_id',
        'teacher_id',
        'creator_id',
        'updater_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->dontSubmitEmptyLogs();
    }

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
        return $this->belongsTo(StudentClass::class, 'student_class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teachers():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(StudentClassAssignment::class, 'section_id');
    }

//    public function studentClass():BelongsTo
//    {
//        return $this->belongsTo(StudentClass::class, 'id');
//    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class);
    }

    public function studentClassAssignments(): HasMany
    {
        return $this->hasMany(StudentClassAssignment::class, 'section_id');
    }
}
