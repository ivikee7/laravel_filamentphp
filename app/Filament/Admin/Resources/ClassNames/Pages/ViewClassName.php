<?php

namespace App\Filament\Admin\Resources\ClassNames\Pages;

use App\Filament\Admin\Resources\ClassNames\ClassNameResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewClassName extends ViewRecord
{
    protected static string $resource = ClassNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
