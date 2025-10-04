<?php

namespace App\Filament\Admin\Resources\BloodGroups\Pages;

use App\Filament\Admin\Resources\BloodGroups\BloodGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBloodGroups extends ListRecords
{
    protected static string $resource = BloodGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
