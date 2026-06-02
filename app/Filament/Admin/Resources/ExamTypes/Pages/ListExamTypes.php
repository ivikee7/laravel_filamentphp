<?php

namespace App\Filament\Admin\Resources\ExamTypes\Pages;

use App\Filament\Admin\Resources\ExamTypes\ExamTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExamTypes extends ListRecords
{
    protected static string $resource = ExamTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
