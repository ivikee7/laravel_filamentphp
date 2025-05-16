<?php

namespace App\Filament\Admin\Resources\StudentResource\RelationManagers;

use App\Models\Cart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('academicYear.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('class.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('INR')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('store.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\Action::make('add_to_cart')
                    ->label('Add to Cart')
                    ->icon('heroicon-o-shopping-cart')
                    ->color('success')
                    ->action(function ($record) {
                        $user = $this->ownerRecord; // This is the parent model of the relation manager

                        // Adjust 'user_id' or 'student_id' depending on your Cart schema
                        $cartItem = Cart::firstOrNew([
                            'user_id'    => $user->id, // or 'student_id' => $student->id
                            'product_id' => $record->id,
                        ]);

                        $cartItem->quantity = ($cartItem->quantity ?? 0) + 1;
                        $cartItem->save();

                        Notification::make()
                            ->title($record->name . ' added to cart for ' . $user->name)
                            ->success()
                            ->send();
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
