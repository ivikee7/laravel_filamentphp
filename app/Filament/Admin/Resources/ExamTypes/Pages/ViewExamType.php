<?php

namespace App\Filament\Admin\Resources\ExamTypes\Pages;

use App\Filament\Admin\Resources\ExamTypes\ExamTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewExamType extends ViewRecord
{
    protected static string $resource = ExamTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
