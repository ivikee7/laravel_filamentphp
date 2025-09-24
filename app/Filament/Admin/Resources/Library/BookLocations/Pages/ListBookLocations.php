<?php

namespace App\Filament\Admin\Resources\Library\BookLocations\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Library\BookLocations\BookLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookLocations extends ListRecords
{
    protected static string $resource = BookLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
