<?php

namespace App\Filament\Resources\Store;

use App\Filament\Resources\Store\StoreProductResource\Pages;
use App\Filament\Resources\Store\StoreProductResource\RelationManagers;
use App\Models\Store\StoreProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreProductResource extends Resource
{
    protected static ?string $model = StoreProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Store Management System';
    protected static ?string $modelLabel = 'Product';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('class_id')
                    ->relationship('class', 'name')
                    ->required(),
                Forms\Components\Select::make('session_id')
                    ->relationship('session', 'name')
                    ->required(),
                Forms\Components\Select::make('store_id')
                    ->relationship('store', 'name')
                    ->required(),
                Forms\Components\Select::make('creator_id')
                    ->relationship('creator', 'name')
                    ->required(),
                Forms\Components\Select::make('updater_id')
                    ->relationship('updater', 'name')
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->searchable(),
                Tables\Columns\TextColumn::make('class.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('session.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('store.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updater.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStoreProducts::route('/'),
            'create' => Pages\CreateStoreProduct::route('/create'),
            'view' => Pages\ViewStoreProduct::route('/{record}'),
            'edit' => Pages\EditStoreProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
