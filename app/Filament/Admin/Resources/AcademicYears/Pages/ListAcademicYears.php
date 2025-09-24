<?php

namespace App\Filament\Admin\Resources\AcademicYears\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\AcademicYears\AcademicYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcademicYears extends ListRecords
{
    protected static string $resource = AcademicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
