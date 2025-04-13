<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class StudentClassAssignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'class_id',
        'section_id',
        'academic_year_id',
        'is_promoted',
        'is_current',
        'creator_id',
        'updater_id',
    ];

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

        static::creating(function ($assignment) {
            // Set all previous assignments for this student as not current
            self::where('student_id', $assignment->student_id)
                ->where('is_current', true)
                ->update(['is_current' => false]);

            // Ensure new record is marked current
            $assignment->is_current = true;
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

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
