<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\FuncCall;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'admission_number',
        'admission_date',
        'current_status',
        'tc_status',
        'leaving_date',
        'exit_reason',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classAssignments()
    {
        return $this->hasMany(StudentClassAssignment::class);
    }
    public function currentClassAssignment()
    {
        return $this->hasOne(StudentClassAssignment::class)->latestOfMany();
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
