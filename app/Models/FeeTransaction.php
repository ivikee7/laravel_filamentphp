<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeTransaction extends Model
{
    protected $fillable = [
        'fee_invoice_id',
        'student_id',
        'amount',
        'method',
        'gateway_driver',
        'status',
        'reference',
        'provider_payment_id',
        'provider_event_id',
        'payment_date',
        'webhook_received_at',
        'last_reconciled_at',
        'reconciliation_status',
        'gateway_payload',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'webhook_received_at' => 'datetime',
        'last_reconciled_at' => 'datetime',
        'gateway_payload' => 'array',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(FeeInvoice::class, 'fee_invoice_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}

