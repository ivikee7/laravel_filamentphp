<?php

namespace App\Filament\Admin\Resources\Quotas\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Quotas\QuotaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQuota extends ViewRecord
{
    protected static string $resource = QuotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
