<?php

namespace App\Filament\Admin\Resources\Transport\RouteResource\Pages;

use App\Filament\Admin\Resources\Transport\RouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRoute extends ViewRecord
{
    protected static string $resource = RouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
