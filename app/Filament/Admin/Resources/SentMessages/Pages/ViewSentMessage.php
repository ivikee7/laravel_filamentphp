<?php

namespace App\Filament\Admin\Resources\SentMessages\Pages;

use App\Filament\Admin\Resources\SentMessages\SentMessageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSentMessage extends ViewRecord
{
    protected static string $resource = SentMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
