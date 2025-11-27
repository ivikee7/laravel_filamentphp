<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions\Schemas;

use App\Models\StoreInvoice;
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
                    ->maxValue(function (Get $get, ?Model $record): ?float {
                        $targetInvoiceId = $get('store_invoice_id');
                        if ($targetInvoiceId) {
                            return StoreInvoice::query()
                                ->where('id', $targetInvoiceId)
                                ->sum('subtotal_amount');
                        }
                        return null;
                    }),
                TextInput::make('remarks')->maxLength(100),
            ]);
    }

}
