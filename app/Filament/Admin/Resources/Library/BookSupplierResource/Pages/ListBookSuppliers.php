<?php

namespace App\Filament\Admin\Resources\Library\BookSupplierResource\Pages;

use App\Filament\Admin\Resources\Library\BookSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookSuppliers extends ListRecords
{
    protected static string $resource = BookSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
