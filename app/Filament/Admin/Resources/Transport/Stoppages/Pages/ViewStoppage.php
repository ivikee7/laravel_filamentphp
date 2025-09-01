<?php

namespace App\Filament\Admin\Resources\Transport\Stoppages\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Transport\Stoppages\StoppageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStoppage extends ViewRecord
{
    protected static string $resource = StoppageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
