<?php

namespace App\Filament\Student\Resources\MyFeeInvoiceResource\Pages;

use App\Filament\Student\Resources\MyFeeInvoiceResource\MyFeeInvoiceResource;
use Filament\Resources\Pages\ListRecords;

class ListMyFeeInvoices extends ListRecords
{
    protected static string $resource = MyFeeInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

