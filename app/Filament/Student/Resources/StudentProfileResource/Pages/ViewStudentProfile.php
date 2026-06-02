<?php

namespace App\Filament\Student\Resources\StudentProfileResource\Pages;

use App\Filament\Student\Resources\StudentProfileResource\StudentProfileResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentProfile extends ViewRecord
{
    protected static string $resource = StudentProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

