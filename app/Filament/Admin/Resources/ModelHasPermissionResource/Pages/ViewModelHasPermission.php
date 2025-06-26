<?php

namespace App\Filament\Admin\Resources\ModelHasPermissionResource\Pages;

use App\Filament\Admin\Resources\ModelHasPermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewModelHasPermission extends ViewRecord
{
    protected static string $resource = ModelHasPermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
