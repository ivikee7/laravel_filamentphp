<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StoreInvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->searchable()->wrap(),
                TextColumn::make('user.id')->label('User Id')->searchable()->wrap(),
                TextColumn::make('user.name')->label('Student')->searchable()->wrap(),
                TextColumn::make('subtotal_amount')->label('Subtotal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('discount_amount')->label('Discount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_amount')->label('Total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_paid_amount')->label('Paid')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_due_amount')->label('Due')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('createdBy.name')->wrap()
                    ->sortable(),
                TextColumn::make('updatedBy.name')
                    ->sortable()->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deletedBy.name')->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->wrap()
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('deleted_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ])->label('More'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
