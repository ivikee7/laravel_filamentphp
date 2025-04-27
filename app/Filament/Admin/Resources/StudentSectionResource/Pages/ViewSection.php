<?php

namespace App\Filament\Admin\Resources\StudentSectionResource\Pages;

use App\Filament\Admin\Resources\StudentSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSection extends ViewRecord
{
    protected static string $resource = StudentSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
