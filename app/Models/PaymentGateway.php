<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'driver',
        'is_enabled',
        'is_default',
        'config',
        'meta',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_default' => 'boolean',
        'config' => 'encrypted:array',
        'meta' => 'encrypted:array',
    ];

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }
}

