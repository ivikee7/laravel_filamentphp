<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\StoreResource\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\StoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStores extends ListRecords
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
