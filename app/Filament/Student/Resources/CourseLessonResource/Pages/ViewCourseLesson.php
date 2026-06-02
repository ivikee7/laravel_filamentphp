<?php

namespace App\Filament\Student\Resources\CourseLessonResource\Pages;

use App\Filament\Student\Resources\CourseLessonResource\CourseLessonResource;
use Filament\Resources\Pages\ViewRecord;

class ViewCourseLesson extends ViewRecord
{
    protected static string $resource = CourseLessonResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

