<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem;

use App\Filament\Admin\Resources\StoreManagementSystem\StoreResource\Pages;
use App\Filament\Admin\Resources\StoreManagementSystem\StoreResource\RelationManagers;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Store Management System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(150),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('state')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('pin_code')
                    ->required()
                    ->maxLength(6),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(100),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->wrap(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()->wrap(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()->wrap(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable()->wrap(),
                Tables\Columns\TextColumn::make('pin_code')
                    ->searchable()->wrap(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()->wrap(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()->wrap(),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            RelationManagers\StudentsRelationManager::class,
            RelationManagers\ProductsRelationManager::class,
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

    public static function getNavigationBadge(): ?string
    {
        return Store::where('is_active', true)->count();
    }
}
