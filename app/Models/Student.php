<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quota(): BelongsTo
    {
        return $this->belongsTo(Quota::class);
    }

    public function class(): belongsTo
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function section(): belongsTo
    {
        return $this->belongsTo(StudentSection::class, 'section_id');
    }

    public function classAssignment(): HasOne
    {
        return $this->hasOne(StudentClassAssignment::class);
    }

//    public function classAssignments():HasMany
//    {
//        return $this->hasMany(StudentClassAssignment::class);
//    }

//    public function currentClassAssignment(): HasOne
//    {
//        return $this->hasOne(StudentClassAssignment::class)->latestOfMany();
//    }
//    public function currentClassAssignment(): HasOne
//    {
//        return $this->hasOne(StudentClassAssignment::class)->latestOfMany('academic_year_id');
//    }


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

    public function registration(): belongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function localGuardian(): belongsTo
    {
        return $this->belongsTo(User::class, 'local_guardian_user_id');
    }

    public function siblings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_siblings', 'student_id', 'sibling_id');
    }
}
