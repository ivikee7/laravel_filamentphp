<?php

namespace App\Filament\Admin\Resources\Library\BookSupplierResource\Pages;

use App\Filament\Admin\Resources\Library\BookSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookSupplier extends EditRecord
{
    protected static string $resource = BookSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
