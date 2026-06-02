<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamAnswer extends Model
{
    protected $fillable = [
        'submission_id',
        'question_id',
        'answer',
        'is_correct',
        'marks_awarded',
    ];

    protected $casts = [
        'is_correct'     => 'boolean',
        'marks_awarded'  => 'decimal:2',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ExamSubmission::class, 'submission_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }
}

