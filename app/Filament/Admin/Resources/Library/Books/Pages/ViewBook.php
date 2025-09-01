<?php

namespace App\Filament\Admin\Resources\Library\Books\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Library\Books\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
