<?php

namespace App\Filament\Admin\Resources\ClassNames\Pages;

use App\Filament\Admin\Resources\ClassNames\ClassNameResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClassNames extends ListRecords
{
    protected static string $resource = ClassNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
