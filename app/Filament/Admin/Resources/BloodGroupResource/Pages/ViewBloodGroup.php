<?php

namespace App\Filament\Admin\Resources\BloodGroupResource\Pages;

use App\Filament\Admin\Resources\BloodGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBloodGroup extends ViewRecord
{
    protected static string $resource = BloodGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
