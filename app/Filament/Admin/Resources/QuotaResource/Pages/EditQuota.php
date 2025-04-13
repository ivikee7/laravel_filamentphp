<?php

namespace App\Filament\Admin\Resources\QuotaResource\Pages;

use App\Filament\Admin\Resources\QuotaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuota extends EditRecord
{
    protected static string $resource = QuotaResource::class;

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
