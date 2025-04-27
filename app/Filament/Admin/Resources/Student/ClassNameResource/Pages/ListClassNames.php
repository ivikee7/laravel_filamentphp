<?php

namespace App\Filament\Admin\Resources\Student\ClassNameResource\Pages;

use App\Filament\Admin\Resources\Student\ClassNameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassNames extends ListRecords
{
    protected static string $resource = ClassNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
