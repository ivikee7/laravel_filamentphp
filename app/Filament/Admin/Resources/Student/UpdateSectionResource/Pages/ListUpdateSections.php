<?php

namespace App\Filament\Admin\Resources\Student\UpdateSectionResource\Pages;

use App\Filament\Admin\Resources\Student\UpdateSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUpdateSections extends ListRecords
{
    protected static string $resource = UpdateSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
