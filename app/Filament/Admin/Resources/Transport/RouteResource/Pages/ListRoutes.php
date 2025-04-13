<?php

namespace App\Filament\Admin\Resources\Transport\RouteResource\Pages;

use App\Filament\Admin\Resources\Transport\RouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoutes extends ListRecords
{
    protected static string $resource = RouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
