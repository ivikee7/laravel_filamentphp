<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores;

use App\Filament\Resources\StoreManagementSystem\Stores\RelationManagers\UsersRelationManager;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\RelationManagers\StudentsRelationManager;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\RelationManagers\ProductsRelationManager;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Pages\ListStores;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Pages\CreateStore;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Pages\ViewStore;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Pages\EditStore;
use App\Filament\Admin\Resources\StoreManagementSystem\StoreResource\Pages;
use App\Filament\Admin\Resources\StoreManagementSystem\StoreResource\RelationManagers;
use App\Models\Store;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Store Management System';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                TextInput::make('address')
                    ->required()
                    ->maxLength(150),
                TextInput::make('city')
                    ->required()
                    ->maxLength(50),
                TextInput::make('state')
                    ->required()
                    ->maxLength(50),
                TextInput::make('pin_code')
                    ->required()
                    ->maxLength(6),
                TextInput::make('phone')
                    ->required()
                    ->maxLength(15),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(100),
                Toggle::make('is_active')
                    ->required()
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()->wrap(),
                TextColumn::make('address')
                    ->searchable()->wrap(),
                TextColumn::make('city')
                    ->searchable()->wrap(),
                TextColumn::make('state')
                    ->searchable()->wrap(),
                TextColumn::make('pin_code')
                    ->searchable()->wrap(),
                TextColumn::make('phone')
                    ->searchable()->wrap(),
                TextColumn::make('email')
                    ->searchable()->wrap(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StudentsRelationManager::class,
            ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStores::route('/'),
            'create' => CreateStore::route('/create'),
            'view' => ViewStore::route('/{record}'),
            'edit' => EditStore::route('/{record}/edit'),
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
