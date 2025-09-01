<?php

namespace App\Filament\Admin\Resources\Library\BookPublishers\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Library\BookPublishers\BookPublisherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookPublishers extends ListRecords
{
    protected static string $resource = BookPublisherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
