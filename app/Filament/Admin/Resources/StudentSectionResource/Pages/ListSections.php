<?php

namespace App\Filament\Admin\Resources\StudentSectionResource\Pages;

use App\Filament\Admin\Resources\StudentSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSections extends ListRecords
{
    protected static string $resource = StudentSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
