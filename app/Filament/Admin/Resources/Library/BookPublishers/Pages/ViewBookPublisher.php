<?php

namespace App\Filament\Admin\Resources\Library\BookPublishers\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Library\BookPublishers\BookPublisherResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookPublisher extends ViewRecord
{
    protected static string $resource = BookPublisherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
