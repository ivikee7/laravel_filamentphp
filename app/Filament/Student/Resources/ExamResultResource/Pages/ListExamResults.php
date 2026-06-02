<?php

namespace App\Filament\Student\Resources\ExamResultResource\Pages;

use App\Filament\Student\Resources\ExamResultResource\ExamResultResource;
use Filament\Resources\Pages\ListRecords;

class ListExamResults extends ListRecords
{
    protected static string $resource = ExamResultResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

