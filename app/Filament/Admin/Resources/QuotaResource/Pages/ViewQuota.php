<?php

namespace App\Filament\Admin\Resources\QuotaResource\Pages;

use App\Filament\Admin\Resources\QuotaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQuota extends ViewRecord
{
    protected static string $resource = QuotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
