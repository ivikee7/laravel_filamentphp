<?php

namespace App\Filament\Admin\Resources\Library\BookSuppliers\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Library\BookSuppliers\BookSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookSupplier extends ViewRecord
{
    protected static string $resource = BookSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
