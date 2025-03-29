<?php

namespace App\Filament\Resources\Store\StoreProductResource\Pages;

use App\Filament\Resources\Store\StoreProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStoreProduct extends ViewRecord
{
    protected static string $resource = StoreProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
