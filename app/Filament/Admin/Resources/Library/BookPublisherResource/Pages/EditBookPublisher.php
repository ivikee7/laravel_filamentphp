<?php

namespace App\Filament\Admin\Resources\Library\BookPublisherResource\Pages;

use App\Filament\Admin\Resources\Library\BookPublisherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookPublisher extends EditRecord
{
    protected static string $resource = BookPublisherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
