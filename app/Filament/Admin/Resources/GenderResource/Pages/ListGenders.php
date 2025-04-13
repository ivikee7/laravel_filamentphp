<?php

namespace App\Filament\Admin\Resources\GenderResource\Pages;

use App\Filament\Admin\Resources\GenderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGenders extends ListRecords
{
    protected static string $resource = GenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
