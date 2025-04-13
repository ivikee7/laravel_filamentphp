<?php

namespace App\Filament\Admin\Resources\Library\BookPublisherResource\Pages;

use App\Filament\Admin\Resources\Library\BookPublisherResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookPublisher extends ViewRecord
{
    protected static string $resource = BookPublisherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
