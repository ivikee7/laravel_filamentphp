<?php

namespace App\Filament\Admin\Resources\Library\BookPublisherResource\Pages;

use App\Filament\Admin\Resources\Library\BookPublisherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookPublishers extends ListRecords
{
    protected static string $resource = BookPublisherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
