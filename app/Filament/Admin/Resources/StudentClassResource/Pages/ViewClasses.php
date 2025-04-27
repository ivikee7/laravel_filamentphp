<?php

namespace App\Filament\Admin\Resources\StudentClassResource\Pages;

use App\Filament\Admin\Resources\StudentClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClasses extends ViewRecord
{
    protected static string $resource = StudentClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
