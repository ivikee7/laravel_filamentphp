<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\WebsiteSetting;
use Illuminate\Database\Eloquent\Collection;

/**
 * Dynamic Website Service
 *
 * Provides convenient methods for working with the dynamic website system
 */
class DynamicWebsiteService
{
    /**
     * Get a published page by slug
     */
    public function getPageBySlug(string $slug): ?Page
    {
        return Page::where('slug', $slug)
            ->where('status', 'published')
            ->with(['sections' => fn($q) => $q->where('is_active', true)->orderBy('order')])
            ->with('seo')
            ->first();
    }

    /**
     * Get the home page
     */
    public function getHomePage(): ?Page
    {
        return Page::where('is_home', true)
            ->where('status', 'published')
            ->with(['sections' => fn($q) => $q->where('is_active', true)->orderBy('order')])
            ->first();
    }

    /**
     * Get all published pages for sitemap/navigation
     */
    public function getAllPublishedPages(): Collection
    {
        return Page::where('status', 'published')
            ->where('show_in_menu', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get a menu with all its items
     */
    public function getMenu(string $slug): ?Menu
    {
        return Menu::where('slug', $slug)
            ->where('is_active', true)
            ->with(['items' => fn($q) => $q->where('is_active', true)->orderBy('order')])
            ->first();
    }

    /**
     * Get menu items as nested structure
     */
    public function getMenuStructure(string $slug): array
    {
        $menu = $this->getMenu($slug);
        if (!$menu) {
            return [];
        }

        return $this->buildMenuTree($menu->items()->get());
    }

    /**
     * Build nested menu tree
     */
    private function buildMenuTree($items, $parentId = null): array
    {
        $tree = [];

        foreach ($items as $item) {
            if ($item->parent_id === $parentId) {
                $item->children = $this->buildMenuTree($items, $item->id);
                $tree[] = $item;
            }
        }

        return $tree;
    }

    /**
     * Get a website setting by key
     */
    public function getSetting(string $key, $default = null)
    {
        return WebsiteSetting::getValue($key, $default);
    }

    /**
     * Set a website setting
     */
    public function setSetting(string $key, $value, string $type = 'string', ?string $group = null, ?string $description = null)
    {
        return WebsiteSetting::setValue($key, $value, $type, $group, $description);
    }

    /**
     * Get all settings by group
     */
    public function getSettingsByGroup(string $group): Collection
    {
        return WebsiteSetting::where('group', $group)->get();
    }

    /**
     * Get all active features by category
     */
    public function getFeaturesByCategory(string $category): Collection
    {
        return \App\Models\Feature::where('category', $category)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get all features
     */
    public function getAllFeatures(): Collection
    {
        return \App\Models\Feature::where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get a slider with all its items
     */
    public function getSlider(string $slug): ?\App\Models\PageSlider
    {
        return \App\Models\PageSlider::where('slug', $slug)
            ->where('is_active', true)
            ->with(['items' => fn($q) => $q->where('is_active', true)->orderBy('order')])
            ->first();
    }

    /**
     * Get website info/company details
     */
    public function getWebsiteInfo(): array
    {
        return [
            'site_name' => $this->getSetting('site_name', config('app.name')),
            'site_description' => $this->getSetting('site_description', ''),
            'site_logo' => $this->getSetting('site_logo'),
            'site_favicon' => $this->getSetting('site_favicon'),
            'company_name' => $this->getSetting('company_name'),
            'company_email' => $this->getSetting('company_email'),
            'company_phone' => $this->getSetting('company_phone'),
            'company_address' => $this->getSetting('company_address'),
            'social_facebook' => $this->getSetting('social_facebook'),
            'social_twitter' => $this->getSetting('social_twitter'),
            'social_instagram' => $this->getSetting('social_instagram'),
            'social_linkedin' => $this->getSetting('social_linkedin'),
        ];
    }

    /**
     * Search pages
     */
    public function searchPages(string $query): Collection
    {
        return Page::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get();
    }
}

