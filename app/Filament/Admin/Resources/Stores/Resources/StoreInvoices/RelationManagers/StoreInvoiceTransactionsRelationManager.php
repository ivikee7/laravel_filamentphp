<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\RelationManagers;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\StoreInvoiceTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StoreInvoiceTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'storeInvoiceTransactions';

    protected static ?string $relatedResource = StoreInvoiceTransactionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
