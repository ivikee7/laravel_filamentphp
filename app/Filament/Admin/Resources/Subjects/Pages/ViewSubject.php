<?php

namespace App\Filament\Admin\Resources\Subjects\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Subjects\SubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSubject extends ViewRecord
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
