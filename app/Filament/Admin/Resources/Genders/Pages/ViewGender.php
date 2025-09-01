<?php

namespace App\Filament\Admin\Resources\Genders\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Genders\GenderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGender extends ViewRecord
{
    protected static string $resource = GenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
