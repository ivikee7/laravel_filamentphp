<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeInvoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'student_id',
        'user_id',
        'period_start',
        'period_end',
        'due_date',
        'sub_total',
        'discount_total',
        'late_fee',
        'total_due',
        'total_paid',
        'status',
        'currency',
        'settings_snapshot',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'due_date' => 'date',
        'sub_total' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'total_due' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'settings_snapshot' => 'array',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(FeeInvoiceItem::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(FeeTransaction::class);
    }

    public function refreshAmounts(): void
    {
        $paid = (float) $this->transactions()->where('status', 'success')->sum('amount');
        $this->total_paid = $paid;

        if ($paid <= 0) {
            $this->status = now()->greaterThan($this->due_date) ? 'overdue' : 'issued';
        } elseif ($paid < (float) $this->total_due) {
            $this->status = 'partial';
        } else {
            $this->status = 'paid';
        }

        $this->save();
    }
}

