<?php

namespace App\Filament\Admin\Resources\StudentClassResource\Pages;

use App\Filament\Admin\Resources\StudentClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClasses extends ListRecords
{
    protected static string $resource = StudentClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
