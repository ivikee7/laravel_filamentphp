<?php

namespace App\Filament\Admin\Resources\Student\ClassNames\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Student\ClassNames\ClassNameResource;
use Filament\Actions;
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
