<?php

namespace App\Filament\Admin\Resources\AcadamicYearResource\Pages;

use App\Filament\Admin\Resources\AcadamicYearResource;
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
