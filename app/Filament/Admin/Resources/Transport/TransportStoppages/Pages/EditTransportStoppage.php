<?php

namespace App\Filament\Admin\Resources\Transport\TransportStoppages\Pages;

use App\Filament\Admin\Resources\Transport\TransportStoppages\TransportStoppageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransportStoppage extends EditRecord
{
    protected static string $resource = TransportStoppageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
