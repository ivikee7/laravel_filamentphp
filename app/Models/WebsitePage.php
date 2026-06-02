<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class WebsitePage extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'website_pages';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'template',
        'website_category_id',
        'status',
        'is_home',
        'show_in_menu',
        'sort_order',
        'meta_title',
        'meta_description',
        'published_at',
    ];

    protected $casts = [
        'is_home' => 'boolean',
        'show_in_menu' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(WebsiteCategory::class, 'website_category_id');
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(WebsiteMenuItem::class, 'website_page_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
        $this->addMediaCollection('gallery');
    }
}
