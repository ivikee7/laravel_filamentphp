<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Schemas;

use App\Models\StoreInvoice;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\Livewire;

class StoreInvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('subtotal_amount')
                    ->numeric()->hiddenLabel()->prefix('Subtotal: '),
                TextEntry::make('discount_amount')
                    ->numeric()->hiddenLabel()->prefix('Discount: '),
                TextEntry::make('total_amount')
                    ->numeric()->hiddenLabel()->prefix('Total: '),
                TextEntry::make('created_by')
                    ->numeric()->hiddenLabel()->prefix('Created By: ')
                    ->placeholder('-'),
                TextEntry::make('updated_by')
                    ->numeric()->hiddenLabel()->prefix('Updated By: ')
                    ->placeholder('-'),
                TextEntry::make('deleted_by')
                    ->numeric()->hiddenLabel()->prefix('Deleted By: ')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()->hiddenLabel()->prefix('Created At: ')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()->hiddenLabel()->prefix('Updated At: ')
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()->hiddenLabel()->prefix('Deleted At: ')
                    ->visible(fn(StoreInvoice $record): bool => $record->trashed()),
                Action::make('discount')
                    ->label('Apply Discount')
                    ->color('primary')
                    ->modalHeading('Apply Discount to Cart')
                    ->form([
                        TextInput::make('discount_amount')
                            ->label('Amount (₹)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(function(Model $record) {
                                return $record->subtotal_amount;
                            })
                            ->helperText('Enter a fixed amount discount.')
                            ->required(),
                        TextInput::make('remarks')
                            ->label('Remarks')
                            ->maxLength(100)
                    ])
                    ->action(function (array $data, Model $record, Component $livewire) {
                        $invoice = StoreInvoice::query()->findOrFail($record->id);
                        $invoice_subtotal_amount = $invoice->subtotal_amount;
                        $invoice_grand_total = $invoice_subtotal_amount - $data['discount_amount'];
                        $invoice->discount_amount = $data['discount_amount'];
                        $invoice->total_amount = $invoice_grand_total;
                        $invoice->remarks = $data['remarks'];
                        $invoice->save();

                        Notification::make()
                            ->title("Discount '{$data['discount_amount']}' applied successfully!")
                            ->success()
                            ->send();

                        $livewire->dispatch('$refresh');
                    }),

            ]);
    }

}
