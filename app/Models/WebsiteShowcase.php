<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WebsiteShowcase extends Model
{
    protected $table = 'website_showcases';

    protected $fillable = [
        'key',
        'menu_placement',
        'payload',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'payload' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActiveForPlacement(Builder $query, string $placement): Builder
    {
        return $query
            ->where('is_active', true)
            ->where('menu_placement', $placement)
            ->orderBy('sort_order');
    }
}
