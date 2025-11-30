<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions\Schemas;

use App\Models\StoreInvoice;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class StoreTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(function (): ?float {
                        return StoreInvoice::query()
                            ->findOrFail(request()->route('store_invoice'))
                            ->total_due_amount;
                    })
                    ->placeholder(function (): ?float {
                        return StoreInvoice::query()
                            ->findOrFail(request()->route('store_invoice'))
                            ->total_due_amount;
                    }),
                TextInput::make('remarks')->maxLength(100),
            ]);
    }
}
