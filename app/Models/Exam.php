<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class Exam extends Model
{
    use SoftDeletes, LogsActivity;
    protected $fillable = [
        'course_id',
        'academic_year_id',
        'title',
        'description',
        'exam_type_id',
        'total_marks',
        'passing_marks',
        'duration_minutes',
        'exam_date',
        'start_time',
        'status',
        'max_attempts',
        'instructions',
    ];
    protected $casts = [
        'exam_date'    => 'date',
        'total_marks'  => 'decimal:2',
        'passing_marks'=> 'decimal:2',
        'max_attempts' => 'integer',
    ];
    // ── Dynamic Type Helpers ─────────────────────────────────────────────────
    public static function getTypeOptions(): array
    {
        return ExamType::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }
    public static function formatTypeLabel(?ExamType $examType): string
    {
        return $examType?->name ?? 'Unspecified';
    }
    public static function resolveTypeColor(?ExamType $examType): string
    {
        $allowed = ['primary', 'gray', 'info', 'success', 'warning', 'danger', 'purple'];
        $color   = $examType?->color;
        return (is_string($color) && in_array($color, $allowed, true)) ? $color : 'gray';
    }
    // ── Attempt Helpers ──────────────────────────────────────────────────────
    /** How many completed attempts (submitted/graded) a student has used. */
    public function studentAttemptCount(int $studentId): int
    {
        return $this->submissions()
            ->where('student_id', $studentId)
            ->whereIn('status', ['submitted', 'graded'])
            ->count();
    }
    /** Whether a student can start a new attempt. */
    public function canStudentAttempt(int $studentId): bool
    {
        if ($this->max_attempts === null) {
            return true; // unlimited
        }
        return $this->studentAttemptCount($studentId) < $this->max_attempts;
    }
    /** Next attempt number for a student. */
    public function nextAttemptNumber(int $studentId): int
    {
        return $this->submissions()
            ->where('student_id', $studentId)
            ->count() + 1;
    }
    /** Human-readable attempts label, e.g. "2 / 3" or "Unlimited". */
    public function attemptsLabel(): string
    {
        return $this->max_attempts === null ? 'Unlimited' : (string) $this->max_attempts;
    }
    // ── Activity Log ─────────────────────────────────────────────────────────
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->dontSubmitEmptyLogs();
    }
    // ── Boot ─────────────────────────────────────────────────────────────────
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (Auth::check()) $model->created_by = Auth::id();
        });
        static::updating(function ($model) {
            if (Auth::check()) $model->updated_by = Auth::id();
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
    // ── Scopes ───────────────────────────────────────────────────────────────
    public function scopePublished($query)  { return $query->where('status', 'published'); }
    public function scopeActive($query)     { return $query->where('status', 'published'); }
    public function scopeUpcoming($query)   { return $query->where('exam_date', '>=', now()->toDateString()); }
    public function scopeCompleted($query)  { return $query->where('status', 'completed'); }
    // ── Accessors ────────────────────────────────────────────────────────────
    public function getPassPercentageAttribute(): float
    {
        if (!$this->total_marks) return 0;
        return round(($this->passing_marks / $this->total_marks) * 100, 1);
    }
    public function getIsUpcomingAttribute(): bool
    {
        return $this->exam_date && $this->exam_date->isFuture();
    }
    public function getTotalQuestionsAttribute(): int
    {
        return $this->questions()->count();
    }
    public function getPassRateAttribute(): ?float
    {
        $total = $this->results()->whereIn('status', ['graded'])->count();
        if (!$total) return null;
        $passed = $this->results()->where('status', 'graded')
            ->where('score', '>=', $this->passing_marks)->count();
        return round(($passed / $total) * 100, 1);
    }
    // ── Relations ────────────────────────────────────────────────────────────
    public function course(): BelongsTo      { return $this->belongsTo(Course::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function examType(): BelongsTo    { return $this->belongsTo(ExamType::class, 'exam_type_id'); }
    public function questions(): HasMany     { return $this->hasMany(ExamQuestion::class)->orderBy('order'); }
    public function results(): HasMany       { return $this->hasMany(ExamResult::class); }
    public function submissions(): HasMany   { return $this->hasMany(ExamSubmission::class); }
    public function createdBy(): BelongsTo   { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy(): BelongsTo   { return $this->belongsTo(User::class, 'updated_by'); }
    public function deletedBy(): BelongsTo   { return $this->belongsTo(User::class, 'deleted_by'); }
}
