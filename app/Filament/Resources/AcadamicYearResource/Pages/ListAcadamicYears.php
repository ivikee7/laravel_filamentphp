<?php

namespace App\Filament\Resources\AcadamicYearResource\Pages;

use App\Filament\Resources\AcadamicYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcadamicYears extends ListRecords
{
    protected static string $resource = AcadamicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
