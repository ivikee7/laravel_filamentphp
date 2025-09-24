<?php

namespace App\Filament\Admin\Resources\Transport\Routes\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Transport\Routes\RouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRoute extends ViewRecord
{
    protected static string $resource = RouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
