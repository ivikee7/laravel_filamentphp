<?php

namespace App\Filament\Admin\Resources\FeeInvoices\Pages;

use App\Filament\Admin\Resources\FeeInvoices\FeeInvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeeInvoices extends ListRecords
{
    protected static string $resource = FeeInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

