<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'color',
        'icon',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ── Scopes ──────────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Accessors ────────────────────────────────────────────────────────────
    public function getExamCountAttribute(): int
    {
        return $this->exams()->count();
    }

    public function getPublishedExamCountAttribute(): int
    {
        return $this->exams()->where('status', 'published')->count();
    }

    // ── Relations ────────────────────────────────────────────────────────────
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'exam_type_id');
    }
}
