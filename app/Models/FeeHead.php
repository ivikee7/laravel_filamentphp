<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeHead extends Model
{
    protected $fillable = [
        'name',
        'code',
        'charge_type',
        'default_amount',
        'is_active',
        'sort_order',
        'meta',
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    public function structureItems(): HasMany
    {
        return $this->hasMany(FeeStructureItem::class);
    }
}

