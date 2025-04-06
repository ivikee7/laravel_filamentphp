<?php

namespace App\Filament\Admin\Resources\SentMessageResource\Pages;

use App\Filament\Admin\Resources\SentMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSentMessage extends ViewRecord
{
    protected static string $resource = SentMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
