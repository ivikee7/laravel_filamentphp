<?php

namespace App\Filament\Admin\Resources\BloodGroupResource\Pages;

use App\Filament\Admin\Resources\BloodGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBloodGroups extends ListRecords
{
    protected static string $resource = BloodGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
