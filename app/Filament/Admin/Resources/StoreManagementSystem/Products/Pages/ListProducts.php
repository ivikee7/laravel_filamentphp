<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Products\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\StoreManagementSystem\Products\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
