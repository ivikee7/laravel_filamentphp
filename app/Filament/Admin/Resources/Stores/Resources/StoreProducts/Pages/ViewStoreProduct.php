<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreProducts\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreProducts\StoreProductResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStoreProduct extends ViewRecord
{
    protected static string $resource = StoreProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
