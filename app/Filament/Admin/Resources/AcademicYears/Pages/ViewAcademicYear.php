<?php

namespace App\Filament\Admin\Resources\AcademicYears\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\AcademicYears\AcademicYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAcademicYear extends ViewRecord
{
    protected static string $resource = AcademicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
