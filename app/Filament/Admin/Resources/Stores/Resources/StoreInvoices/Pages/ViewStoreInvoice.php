<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\StoreInvoiceResource;
use App\Models\StoreInvoice;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ViewStoreInvoice extends ViewRecord
{
    protected static string $resource = StoreInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
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
        ];
    }

    protected $listeners = ['$refresh' => '$refresh'];
}
