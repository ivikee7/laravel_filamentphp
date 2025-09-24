<?php

namespace App\Filament\Admin\Resources\StudentSections\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\StudentSections\StudentSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSection extends ViewRecord
{
    protected static string $resource = StudentSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
