<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\InvoiceResource\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
