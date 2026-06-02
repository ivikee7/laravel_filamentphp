<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class ExamQuestion extends Model
{
    use LogsActivity;
    protected $fillable = [
        'exam_id',
        'question',
        'type',
        'marks',
        'order',
        'options',
        'shuffle_options',
        'correct_answer',
        'explanation',
    ];
    protected $casts = [
        'options' => 'array',
        'shuffle_options' => 'boolean',
        'marks' => 'decimal:2',
    ];
    public static function typeOptions(): array
    {
        return [
            'multiple_choice' => '🔘 Multiple Choice',
            'true_false' => '✅ True / False',
            'short_answer' => '✏️ Short Answer',
            'essay' => '📄 Essay / Long Answer',
        ];
    }
    public static function typeColor(string $type): string
    {
        return match ($type) {
            'multiple_choice' => 'info',
            'true_false' => 'success',
            'short_answer' => 'warning',
            'essay' => 'gray',
            default => 'gray',
        };
    }
    public static function typeLabel(string $type): string
    {
        return match ($type) {
            'multiple_choice' => '🔘 MCQ',
            'true_false' => '✅ T/F',
            'short_answer' => '✏️ Short',
            'essay' => '📄 Essay',
            default => ucfirst($type),
        };
    }
    public static function typeIcon(string $type): string
    {
        return match ($type) {
            'multiple_choice' => 'heroicon-m-check-badge',
            'true_false' => 'heroicon-m-check-circle',
            'short_answer' => 'heroicon-m-pencil-square',
            'essay' => 'heroicon-m-document-text',
            default => 'heroicon-m-question-mark-circle',
        };
    }
    public function isObjective(): bool
    {
        return in_array($this->type, ['multiple_choice', 'true_false'], true);
    }
    public function supportsShuffle(): bool
    {
        return $this->type === 'multiple_choice';
    }
    public function supportsAutoGrading(): bool
    {
        return in_array($this->type, ['multiple_choice', 'true_false'], true);
    }
    public function getDisplayOptionsAttribute(): array
    {
        $options = is_array($this->options) ? $this->options : [];
        if ($this->type === 'true_false' && $options === []) {
            $options = [
                'true' => '✅ True',
                'false' => '❌ False',
            ];
        }
        if ($this->type === 'multiple_choice' && $this->shuffle_options && $options !== []) {
            $keys = array_keys($options);
            shuffle($keys);
            $shuffled = [];
            foreach ($keys as $key) {
                $shuffled[$key] = $options[$key];
            }
            return $shuffled;
        }
        return $options;
    }
    public function getAnswerModeLabelAttribute(): string
    {
        return $this->supportsAutoGrading() ? 'Auto-graded' : 'Manual review';
    }
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
        });
    }
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
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
