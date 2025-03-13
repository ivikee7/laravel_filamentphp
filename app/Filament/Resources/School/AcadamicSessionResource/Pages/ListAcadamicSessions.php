<?php

namespace App\Filament\Resources\School\AcadamicSessionResource\Pages;

use App\Filament\Resources\School\AcadamicSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcadamicSessions extends ListRecords
{
    protected static string $resource = AcadamicSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
