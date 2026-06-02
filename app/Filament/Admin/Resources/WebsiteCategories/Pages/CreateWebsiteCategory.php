<?php

namespace App\Filament\Admin\Resources\WebsiteCategories\Pages;

use App\Filament\Admin\Resources\WebsiteCategories\WebsiteCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWebsiteCategory extends CreateRecord
{
    protected static string $resource = WebsiteCategoryResource::class;
}
