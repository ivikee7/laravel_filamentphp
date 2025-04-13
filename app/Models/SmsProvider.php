<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SmsProvider extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'base_url',
        'method',
        'params',
        'headers',
        'responses',
        'is_active',
        'to_key',
        'text_key',
        'creator_id',
        'updater_id',
    ];

    protected $casts = [
        'params' => 'array',
        'headers' => 'array',
        'responses' => 'array',
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



    public static function getActiveProviders()
    {
        return self::where('is_active', true)->pluck('name', 'id');
    }
}
