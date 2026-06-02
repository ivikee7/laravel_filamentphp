<?php

namespace App\Filament\Student\Resources\StudentProfileResource\Pages;

use App\Filament\Student\Resources\StudentProfileResource\StudentProfileResource;
use Filament\Resources\Pages\ListRecords;

class ListStudentProfiles extends ListRecords
{
    protected static string $resource = StudentProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

