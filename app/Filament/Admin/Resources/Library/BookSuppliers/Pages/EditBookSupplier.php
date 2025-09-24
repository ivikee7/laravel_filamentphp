<?php

namespace App\Filament\Admin\Resources\Library\BookSuppliers\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Admin\Resources\Library\BookSuppliers\BookSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookSupplier extends EditRecord
{
    protected static string $resource = BookSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
