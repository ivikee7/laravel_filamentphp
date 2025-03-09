<?php

namespace App\Filament\Resources\Store\StoreResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(isIndividual: true)
                    ->label('Invoice')
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('gross_total')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_total')
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_total')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rematks')
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->formatStateUsing(function ($record) {
                        return $record->creator->name . ' (' . $record->creator->id . ')';
                    })
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updater.name')
                    ->formatStateUsing(function ($record) {
                        return $record->updater->name . ' (' . $record->updater->id . ')';
                    })
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
