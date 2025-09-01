<?php

namespace App\Filament\Admin\Resources\StudentClasses\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\StudentClasses\StudentClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClasses extends ViewRecord
{
    protected static string $resource = StudentClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
