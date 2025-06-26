<?php

namespace App\Filament\Admin\Resources\ModelHasPermissionResource\Pages;

use App\Filament\Admin\Resources\ModelHasPermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModelHasPermission extends EditRecord
{
    protected static string $resource = ModelHasPermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
