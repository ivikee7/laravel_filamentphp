<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'message',
        'response',
        'provider_id',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function provider(): BelongsTo
    {
        return $this->belongsTo(SmsProvider::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
