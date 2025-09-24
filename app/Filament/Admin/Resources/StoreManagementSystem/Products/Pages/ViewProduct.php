<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Products\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\StoreManagementSystem\Products\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
