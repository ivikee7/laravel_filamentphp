<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebsiteMenu extends Model
{
    protected $table = 'website_menus';

    protected $fillable = [
        'name',
        'slug',
        'location',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(WebsiteMenuItem::class, 'website_menu_id')->orderBy('sort_order');
    }

    public function rootItems(): HasMany
    {
        return $this->hasMany(WebsiteMenuItem::class, 'website_menu_id')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function mainNavigationMenu(): ?self
    {
        return static::query()
            ->active()
            ->whereKey(1)
            ->first();
    }
}
