<?php

namespace App\Filament\Admin\Resources\QuotaResource\Pages;

use App\Filament\Admin\Resources\QuotaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuotas extends ListRecords
{
    protected static string $resource = QuotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
