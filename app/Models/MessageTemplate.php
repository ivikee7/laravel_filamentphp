<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'content',
        'variables',
        'params',
        'sms_provider_id',
        'is_active'
    ];

    protected $casts = [
        'variables' => 'array', // Ensure variables are stored as JSON
        'params' => 'array',
    ];

    /**
     * Replace placeholders with actual values.
     */
    public function render(array $data): string
    {
        $message = $this->content;
        foreach ($data as $key => $value) {
            $message = str_replace("{{{$key}}}", $value, $message);
        }
        return $message;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->creator_id = auth()->id();
            }
        });
        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updater_id = auth()->id();
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function smsProvider(): BelongsTo
    {
        return $this->belongsTo(SmsProvider::class);
    }
}
