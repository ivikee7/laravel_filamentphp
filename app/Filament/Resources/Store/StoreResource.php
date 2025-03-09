<?php

namespace App\Filament\Resources\Store;

use App\Filament\Resources\Store\StoreResource\Pages;
use App\Filament\Resources\Store\StoreResource\RelationManagers;
use App\Filament\Resources\Store\StoreResource\RelationManagers\CustomersRelationManager;
use App\Filament\Resources\Store\StoreResource\RelationManagers\InvoicesRelationManager;
use App\Filament\Resources\Store\StoreResource\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\Store\StoreResource\RelationManagers\TransactionsRelationManager;
use App\Filament\Resources\Store\StoreResource\RelationManagers\TransationsRelationManager;
use App\Models\Store\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Guava\FilamentNestedResources\Concerns\NestedResource;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentNestedResources\Ancestor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreResource extends Resource
{
    use NestedResource;

    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Store Management System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('creator_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('updater_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('created_at'),
                Forms\Components\DateTimePicker::make('updated_at'),
                Forms\Components\DateTimePicker::make('deleted_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->formatStateUsing(function ($record) {
                        return $record->creator->name . ' (' . $record->creator->id . ')';
                    })
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->label('Creator')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updater.name')
                    ->formatStateUsing(function ($record) {
                        return $record->creator->name . ' (' . $record->creator->id . ')';
                    })
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->label('Updater')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->label('Created At')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->label('Updated At')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->label('Deleted At')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
            InvoicesRelationManager::class,
            TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'view' => Pages\ViewStore::route('/{record}'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getAncestor(): ?Ancestor
    {
        return null;
    }
}
