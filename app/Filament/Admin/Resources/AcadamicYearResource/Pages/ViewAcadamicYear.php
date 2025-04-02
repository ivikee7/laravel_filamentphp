<?php

namespace App\Filament\Admin\Resources\AcadamicYearResource\Pages;

use App\Filament\Admin\Resources\AcadamicYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAcadamicYear extends ViewRecord
{
    protected static string $resource = AcadamicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
