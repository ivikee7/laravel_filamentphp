<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Schemas;

use App\Models\StoreInvoiceTransaction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StoreInvoiceTransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('method'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('remarks')
                    ->placeholder('-'),
                TextEntry::make('createdBy.name')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('updatedBy.name')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('deletedBy.name')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (StoreInvoiceTransaction $record): bool => $record->trashed()),
            ]);
    }
}
