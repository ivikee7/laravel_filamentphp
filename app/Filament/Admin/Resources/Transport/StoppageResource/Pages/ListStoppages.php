<?php

namespace App\Filament\Admin\Resources\Transport\StoppageResource\Pages;

use App\Filament\Admin\Resources\Transport\StoppageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoppages extends ListRecords
{
    protected static string $resource = StoppageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
