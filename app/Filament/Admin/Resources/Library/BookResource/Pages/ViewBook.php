<?php

namespace App\Filament\Admin\Resources\Library\BookResource\Pages;

use App\Filament\Admin\Resources\Library\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
