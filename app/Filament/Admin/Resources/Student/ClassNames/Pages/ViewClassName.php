<?php

namespace App\Filament\Admin\Resources\Student\ClassNames\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Student\ClassNames\ClassNameResource;
use Filament\Actions;
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
