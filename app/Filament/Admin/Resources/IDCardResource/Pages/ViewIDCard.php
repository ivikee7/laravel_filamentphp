<?php

namespace App\Filament\Admin\Resources\IDCardResource\Pages;

use App\Filament\Admin\Resources\IDCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewIDCard extends ViewRecord
{
    protected static string $resource = IDCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
