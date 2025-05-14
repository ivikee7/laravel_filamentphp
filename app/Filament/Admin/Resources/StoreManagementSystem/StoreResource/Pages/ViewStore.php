<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\StoreResource\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\StoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStore extends ViewRecord
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
