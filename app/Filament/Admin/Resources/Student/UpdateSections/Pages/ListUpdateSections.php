<?php

namespace App\Filament\Admin\Resources\Student\UpdateSections\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Student\UpdateSections\UpdateSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUpdateSections extends ListRecords
{
    protected static string $resource = UpdateSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
