<?php

namespace App\Filament\Student\Resources\MyCourseResource\Pages;

use App\Filament\Student\Resources\MyCourseResource\MyCourseResource;
use Filament\Resources\Pages\ListRecords;

class ListMyCourses extends ListRecords
{
    protected static string $resource = MyCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
