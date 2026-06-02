<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebsiteMenuItem extends Model
{
    protected $table = 'website_menu_items';

    protected $fillable = [
        'website_menu_id',
        'website_page_id',
        'parent_id',
        'label',
        'url',
        'target',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(WebsiteMenu::class, 'website_menu_id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(WebsitePage::class, 'website_page_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->where('is_active', true)->with('childrenRecursive');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getDepthAttribute(): int
    {
        $depth = 0;
        $parent = $this->parent;
        $safetyCounter = 0;

        while ($parent !== null && $safetyCounter < 10) {
            $depth++;
            $parent = $parent->parent;
            $safetyCounter++;
        }

        return $depth;
    }

    public function getLabelWithDepthAttribute(): string
    {
        return str_repeat('-- ', $this->depth).$this->label;
    }

    public function getItemTypeAttribute(): string
    {
        if (filled($this->website_page_id)) {
            return 'page';
        }

        if (filled($this->url)) {
            return 'link';
        }

        return 'category';
    }

    public function getDestinationAttribute(): string
    {
        if ($this->page?->slug) {
            return '/'.$this->page->slug;
        }

        return (string) ($this->url ?: '#');
    }
}
