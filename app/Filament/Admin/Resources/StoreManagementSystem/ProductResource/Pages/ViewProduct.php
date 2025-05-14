<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\ProductResource\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
