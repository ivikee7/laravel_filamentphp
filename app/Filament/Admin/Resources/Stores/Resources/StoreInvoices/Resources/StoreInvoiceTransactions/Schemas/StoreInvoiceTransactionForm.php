<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Schemas;

use App\Models\StoreInvoice;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

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
                    ->minValue(0)
                    ->maxValue(function (Get $get, ?Model $record) {
                        $invoiceId = $get('store_invoice_id');

                        if (!$invoiceId) {
                            return null;
                        }

                        $store_invoice = StoreInvoice::query()->find($invoiceId);

                        if (!$store_invoice) {
                            return null;
                        }

                        $remaining_balance = ($store_invoice->total_amount - $store_invoice->total_paid_amount);

                        if ($record && $record->exists) {
                            $remaining_balance += $record->amount;
                        }

                        return $remaining_balance;
                    }),
                TextInput::make('remarks')
                    ->default(null),
            ]);
    }
}
