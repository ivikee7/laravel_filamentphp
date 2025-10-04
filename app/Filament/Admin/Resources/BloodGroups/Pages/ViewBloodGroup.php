<?php

namespace App\Filament\Admin\Resources\BloodGroups\Pages;

use App\Filament\Admin\Resources\BloodGroups\BloodGroupResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBloodGroup extends ViewRecord
{
    protected static string $resource = BloodGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
