<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ExamResult extends Model
{
    use LogsActivity;

    protected $fillable = [
        'exam_id',
        'participant_type',
        'student_id',
        'registration_id',
        'participant_name',
        'participant_email',
        'score',
        'grade',
        'remarks',
        'graded_at',
        'graded_by',
        'status',
    ];

    protected $casts = [
        'score'      => 'decimal:2',
        'graded_at'  => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->dontSubmitEmptyLogs();
    }

    protected static function boot(): void
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
            if ($model->isDirty('score') && $model->score !== null) {
                $model->graded_at = now();
                $model->graded_by = Auth::id();
                $model->status = 'graded';
            }
        });
    }

    /** Resolved display name regardless of participant type */
    public function getParticipantLabelAttribute(): string
    {
        return match ($this->participant_type) {
            'student'   => $this->student?->user?->name
                           ?? $this->student?->admission_number
                           ?? '—',
            'applicant' => $this->registration?->name ?? '—',
            'external'  => filled($this->participant_name) ? $this->participant_name : '—',
            default     => '—',
        };
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function gradedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
