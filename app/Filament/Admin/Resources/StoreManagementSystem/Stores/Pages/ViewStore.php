<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\StoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStore extends ViewRecord
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
