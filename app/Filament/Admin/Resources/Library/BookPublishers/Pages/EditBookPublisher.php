<?php

namespace App\Filament\Admin\Resources\Library\BookPublishers\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Admin\Resources\Library\BookPublishers\BookPublisherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookPublisher extends EditRecord
{
    protected static string $resource = BookPublisherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
