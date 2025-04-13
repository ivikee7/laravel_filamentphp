<?php

namespace App\Filament\Admin\Resources\Transport\StoppageResource\Pages;

use App\Filament\Admin\Resources\Transport\StoppageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStoppage extends ViewRecord
{
    protected static string $resource = StoppageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
