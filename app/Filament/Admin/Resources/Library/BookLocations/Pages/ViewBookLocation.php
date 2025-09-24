<?php

namespace App\Filament\Admin\Resources\Library\BookLocations\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Library\BookLocations\BookLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookLocation extends ViewRecord
{
    protected static string $resource = BookLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
