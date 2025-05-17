<?php

namespace App\Filament\Admin\Resources\StudentResource\RelationManagers;

use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CartRelationManager extends RelationManager
{
    protected static string $relationship = 'cart';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
//            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('product.academicYear.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.class.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.price')->label('Price')
                    ->money('INR')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')->label('Qty'),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->getStateUsing(fn($record) => $record->product?->price * $record->quantity)
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.store.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('createInvoice')
                    ->label('Create Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function () {
                        $user = $this->ownerRecord;

                        // You can implement your own InvoiceService or model logic here.
                        // For example:
                        $cartItems = $user->cart()->with('product')->get();
                        $total = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

                        // Example: Create an invoice record
                        $invoice = Invoice::create([
                            'user_id' => $user->id,
                            'store_id' => '',
                            'invoice_date' => now(),
                            'subtotal'=>$total,
                            'invoiceable_type'=>get_class($user->invoiceable),
                            'invoiceable_id'=>$user->invoiceable_id,
                            'total' => $total,
                            'status' => 'pending',
                        ]);

                        // Attach products or line items
                        foreach ($cartItems as $item) {
                            $invoice->items()->create([
                                'invoice_id'=>$invoice->id,
                                'product_id' => $item->product_id,
                                'quantity' => $item->quantity,
                                'price' => $item->product->price,
                                'total' => $item->product->price * $item->quantity,
                            ]);
                        }

                        // Optional: Clear cart after invoice generation
                        $user->cart()->delete();

                        Notification::make()
                            ->title('Invoice created successfully!')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('clearCart')
                    ->label('Clear Cart')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function () {
                        $student = $this->ownerRecord;

                        $student->cart()->delete();

                        Notification::make()
                            ->title('Cart cleared successfully!')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('showTotal')
                    ->label(fn () => 'Total: ₹' . number_format(
                            $this->ownerRecord->cart->sum(fn ($item) => $item->product->price * $item->quantity),
                            2
                        ))
                    ->disabled()
                    ->color('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('decrement')
                    ->label('-')
                    ->badge()
                    ->action(function ($record) {
                        if ($record->quantity > 1) {
                            $record->decrement('quantity');
                        } else {
                            $record->delete(); // Optionally delete if quantity reaches 0
                        }
                    }),

                Tables\Actions\Action::make('increment')
                    ->label('+')
                    ->badge()
                    ->action(function ($record) {
                        $record->increment('quantity');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
