<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
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
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }





    public function smsProvider(): BelongsTo
    {
        return $this->belongsTo(SmsProvider::class);
    }
}
