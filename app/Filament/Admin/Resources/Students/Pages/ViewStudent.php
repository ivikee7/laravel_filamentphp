<?php

namespace App\Filament\Admin\Resources\Students\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Students\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
