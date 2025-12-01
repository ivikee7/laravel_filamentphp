<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StoreInvoiceTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('method')
                    ->label('Payment Method')
                    ->options([
                        'cash' => 'Cash',
                        'upi' => 'UPI',
                        'bank' => 'Bank',
                    ])
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('remarks')
                    ->default(null),
            ]);
    }
}
