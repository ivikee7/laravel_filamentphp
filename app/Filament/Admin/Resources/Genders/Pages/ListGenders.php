<?php

namespace App\Filament\Admin\Resources\Genders\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Genders\GenderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGenders extends ListRecords
{
    protected static string $resource = GenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
