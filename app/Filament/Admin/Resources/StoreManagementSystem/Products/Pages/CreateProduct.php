<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Products\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\Products\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
