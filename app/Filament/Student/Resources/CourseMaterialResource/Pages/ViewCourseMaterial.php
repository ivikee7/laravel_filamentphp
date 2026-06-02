<?php

namespace App\Filament\Student\Resources\CourseMaterialResource\Pages;

use App\Filament\Student\Resources\CourseMaterialResource\CourseMaterialResource;
use Filament\Resources\Pages\ViewRecord;

class ViewCourseMaterial extends ViewRecord
{
    protected static string $resource = CourseMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

