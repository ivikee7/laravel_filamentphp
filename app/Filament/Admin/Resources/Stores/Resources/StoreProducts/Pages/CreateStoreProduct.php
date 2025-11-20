<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreProducts\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreProducts\StoreProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStoreProduct extends CreateRecord
{
    protected static string $resource = StoreProductResource::class;
}
