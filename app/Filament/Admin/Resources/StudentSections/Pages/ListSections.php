<?php

namespace App\Filament\Admin\Resources\StudentSections\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\StudentSections\StudentSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSections extends ListRecords
{
    protected static string $resource = StudentSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
