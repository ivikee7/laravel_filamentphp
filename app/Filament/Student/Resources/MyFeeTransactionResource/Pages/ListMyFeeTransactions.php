<?php

namespace App\Filament\Student\Resources\MyFeeTransactionResource\Pages;

use App\Filament\Student\Resources\MyFeeTransactionResource\MyFeeTransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListMyFeeTransactions extends ListRecords
{
    protected static string $resource = MyFeeTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

