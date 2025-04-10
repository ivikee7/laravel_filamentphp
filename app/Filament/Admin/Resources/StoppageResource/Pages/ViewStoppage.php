<?php

namespace App\Filament\Admin\Resources\StoppageResource\Pages;

use App\Filament\Admin\Resources\StoppageResource;
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
