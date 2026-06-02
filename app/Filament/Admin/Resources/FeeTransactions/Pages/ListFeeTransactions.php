<?php

namespace App\Filament\Admin\Resources\FeeTransactions\Pages;

use App\Filament\Admin\Resources\FeeTransactions\FeeTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeeTransactions extends ListRecords
{
    protected static string $resource = FeeTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

