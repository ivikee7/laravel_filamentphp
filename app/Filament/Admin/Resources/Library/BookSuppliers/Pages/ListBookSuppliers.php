<?php

namespace App\Filament\Admin\Resources\Library\BookSuppliers\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Library\BookSuppliers\BookSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookSuppliers extends ListRecords
{
    protected static string $resource = BookSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
