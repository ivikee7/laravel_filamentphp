<?php

namespace App\Filament\Admin\Resources\SentMessageResource\Pages;

use App\Filament\Admin\Resources\SentMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSentMessage extends EditRecord
{
    protected static string $resource = SentMessageResource::class;

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
