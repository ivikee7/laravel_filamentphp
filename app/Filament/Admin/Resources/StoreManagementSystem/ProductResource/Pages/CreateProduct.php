<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\ProductResource\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
