<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentFeeProfile extends Model
{
    protected $fillable = [
        'student_id',
        'fee_structure_id',
        'scholarship_type',
        'scholarship_value',
        'sibling_discount_percent',
        'custom_settings',
        'is_active',
    ];

    protected $casts = [
        'scholarship_value' => 'decimal:2',
        'sibling_discount_percent' => 'decimal:2',
        'custom_settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function structure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class, 'fee_structure_id');
    }
}

