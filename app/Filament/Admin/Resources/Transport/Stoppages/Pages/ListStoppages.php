<?php

namespace App\Filament\Admin\Resources\Transport\Stoppages\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Transport\Stoppages\StoppageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoppages extends ListRecords
{
    protected static string $resource = StoppageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
