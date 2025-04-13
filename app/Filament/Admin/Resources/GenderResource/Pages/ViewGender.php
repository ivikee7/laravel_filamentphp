<?php

namespace App\Filament\Admin\Resources\GenderResource\Pages;

use App\Filament\Admin\Resources\GenderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGender extends ViewRecord
{
    protected static string $resource = GenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
