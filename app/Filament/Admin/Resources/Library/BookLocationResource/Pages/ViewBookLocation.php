<?php

namespace App\Filament\Admin\Resources\Library\BookLocationResource\Pages;

use App\Filament\Admin\Resources\Library\BookLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookLocation extends ViewRecord
{
    protected static string $resource = BookLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
