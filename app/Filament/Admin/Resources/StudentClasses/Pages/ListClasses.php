<?php

namespace App\Filament\Admin\Resources\StudentClasses\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\StudentClasses\StudentClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClasses extends ListRecords
{
    protected static string $resource = StudentClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
