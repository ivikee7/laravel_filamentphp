<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class WhatsAppProvider extends Model
{
    use SoftDeletes;

    protected $table = 'whatsapp_providers';

    protected $fillable = [
        // 🔧 Basic Config
        'name',
        'base_url',
        'send_message_endpoint',
        'api_token',
        'headers',
        'verify_token',
        'encryption_key',
        // 📞 WhatsApp API Details
        'phone_number',
        'phone_number_id',
        'business_account_id',
        'token_expires_at',
        // 🔁 Webhook Management
        'webhook_url',
        'webhook_received_at',
        'webhook_status',
        'last_error_message',
        'failed_webhook_count',
        'last_successful_response',
        // 🔐 Meta App Info
        'meta_app_id',
        'meta_app_secret',
        // ⚙️ Operational Controls
        'is_active',
        'is_default',
        'environment',
        // 👤 Auditing
        'created_by',
        'updated_by',
        'deleted_by',

    ];

    protected $casts = [
        'headers' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

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

    public function isActive()
    {
        return $this->where('is_active', true);
    }
    public function default()
    {
        return $this->where('is_default', true)->first();
    }
}
