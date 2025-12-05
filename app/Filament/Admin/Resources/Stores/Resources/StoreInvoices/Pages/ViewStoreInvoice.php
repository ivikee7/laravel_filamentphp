<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\StoreInvoiceResource;
use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\StoreInvoice;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Group;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ViewStoreInvoice extends ViewRecord
{
    protected static string $resource = StoreInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('make-payment')
                ->label('Payment')
                ->modalHeading('Invoice payment')
                // Define the form fields directly
                ->schema([
                    Group::make([
                        TextInput::make('amount')
                            ->label('Amount (₹)')
                            ->minValue(0)
                            // Use 'Get' to access other form values if needed, but 'Model $record'
                            // still refers to the current table record for the max value calculation.
                            ->maxValue(function (Model $record) {
                                // Ensure these attributes exist on your StoreInvoice model
                                return ($record->subtotal_amount - $record->discount_amount - $record->total_paid_amount);
                            })
                            ->numeric() // Add numeric validation if it's a number field
                            ->required(),
                        Select::make('method')->options([
                            'bank' => 'Bank',
                            'cash' => 'Cash',
                            'upi' => 'UPI',
                        ]),
                        TextInput::make('remarks')
                            ->label('Remarks')
                            ->maxLength(100),
                    ])->columns(2)
                ])
                // Define what happens when the action is submitted
                ->action(function (array $data, Model $record): void {
                    // Use the $data array (which contains 'amount' and 'remarks')
                    // and the $record (the current StoreInvoice) to create the transaction.

                    $record->storeInvoiceTransactions()->create([
                        'amount' => $data['amount'],
                        'remarks' => $data['remarks'],
                        'method' => $data['method'],
                        'created_by' => auth()->id(),
                    ]);

                    // You might want to dispatch a notification or refresh the page here
                }),
            Action::make('discount')
                ->label('Apply Discount')
                ->color('primary')
                ->modalHeading('Apply Discount to Cart')
                ->schema([
                    TextInput::make('discount_amount')
                        ->label('Amount (₹)')
                        ->numeric()
                        ->default(function (Model $record) {
                            return $record->discount_amount;
                        })
                        ->minValue(0)
                        ->maxValue(function (Model $record) {
                            return ($record->subtotal_amount - $record->total_paid_amount);
                        })
                        ->helperText('Enter a fixed amount discount.')
                        ->required(),
                    TextInput::make('remarks')
                        ->label('Remarks')
                        ->default(function (Model $record) {
                            return $record->remarks;
                        })
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
            Action::make('print-invoice')
                ->url(function (Model $record): string {
                    return StoreResource::getUrl('print-invoice', [
                        'record' => $record->store_id,
                        'invoiceId' => $record->id
                    ]);
                })
                ->openUrlInNewTab(),
        ];
    }

    protected $listeners = ['$refresh' => '$refresh'];
}
