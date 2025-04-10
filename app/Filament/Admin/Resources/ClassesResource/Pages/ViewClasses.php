<?php

namespace App\Filament\Admin\Resources\ClassesResource\Pages;

use App\Filament\Admin\Resources\ClassesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClasses extends ViewRecord
{
    protected static string $resource = ClassesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
