<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'admission_number',
        'registration_id',
        'current_status',
        'tc_status',
        'leaving_date',
        'exit_reason',
        'quota_id',
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


    public function quota()
    {
        return $this->belongsTo(Quota::class);
    }

    public function classAssignments()
    {
        return $this->hasMany(StudentClassAssignment::class);
    }
    public function currentClassAssignment()
    {
        $activeYearId = \App\Models\AcademicYear::where('is_active', true)->value('id');

        return $this->hasOne(StudentClassAssignment::class)
            ->where(function ($query) use ($activeYearId) {
                $query->where('is_current', true)
                    ->where('academic_year_id', $activeYearId);
            })
            ->orWhere(function ($query) {
                // Fallback to latest assignment if no active-year assignment exists
                $query->where('is_current', true)
                    ->orderByDesc('academic_year_id');
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentAcademicYear()
    {
        return $this->hasOneThrough(
            AcademicYear::class,
            StudentClassAssignment::class,
            'student_id',        // Foreign key on StudentClassAssignment
            'id',                // Local key on AcademicYear
            'id',                // Local key on Student
            'academic_year_id'   // Foreign key on StudentClassAssignment
        )->where('is_current', true);
    }
}
