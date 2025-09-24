<?php

namespace App\Filament\Admin\Resources\Subjects\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Subjects\SubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
