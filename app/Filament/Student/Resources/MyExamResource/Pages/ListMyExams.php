<?php

namespace App\Filament\Student\Resources\MyExamResource\Pages;

use App\Filament\Student\Resources\MyExamResource\MyExamResource;
use Filament\Resources\Pages\ListRecords;

class ListMyExams extends ListRecords
{
    protected static string $resource = MyExamResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
