<?php

namespace App\Filament\Admin\Resources\ModelHasPermissionResource\Pages;

use App\Filament\Admin\Resources\ModelHasPermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModelHasPermissions extends ListRecords
{
    protected static string $resource = ModelHasPermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
