<?php

namespace App\Filament\Admin\Pages\Website;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class Menu
{
    /**
     * This page has been replaced by WebsiteMenuResource
     * Redirect to the resource
     */
    public static function redirectToResource(): RedirectResponse
    {
        return Redirect::to('/admin/website-menus');
    }
}

