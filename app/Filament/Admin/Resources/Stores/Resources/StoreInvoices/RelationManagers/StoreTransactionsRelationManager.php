<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\RelationManagers;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions\StoreTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StoreTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'storeTransactions';

    protected static ?string $relatedResource = StoreTransactionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
