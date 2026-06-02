<?php

namespace App\Filament\Student\Resources\CourseLessonResource\Pages;

use App\Filament\Student\Resources\CourseLessonResource\CourseLessonResource;
use Filament\Resources\Pages\ListRecords;

class ListCourseLessons extends ListRecords
{
    protected static string $resource = CourseLessonResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

