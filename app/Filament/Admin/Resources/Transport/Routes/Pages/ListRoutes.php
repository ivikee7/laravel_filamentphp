<?php

namespace App\Filament\Admin\Resources\Transport\Routes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Transport\Routes\RouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoutes extends ListRecords
{
    protected static string $resource = RouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
