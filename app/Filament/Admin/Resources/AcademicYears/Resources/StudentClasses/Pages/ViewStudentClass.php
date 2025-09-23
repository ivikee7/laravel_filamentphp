<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Pages;

use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\StudentClassResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentClass extends ViewRecord
{
    protected static string $resource = StudentClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
