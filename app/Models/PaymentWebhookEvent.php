<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentWebhookEvent extends Model
{
    protected $fillable = [
        'driver',
        'payload_hash',
        'event_id',
        'payment_reference',
        'provider_payment_id',
        'signature_valid',
        'status',
        'attempts',
        'headers',
        'payload',
        'error_message',
        'processed_at',
    ];

    protected $casts = [
        'signature_valid' => 'boolean',
        'headers' => 'array',
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];
}

