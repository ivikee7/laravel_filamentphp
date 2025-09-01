<?php

namespace App\Filament\Admin\Resources\Library\BookSuppliers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\Library\BookSuppliers\Pages\ListBookSuppliers;
use App\Filament\Admin\Resources\Library\BookSuppliers\Pages\CreateBookSupplier;
use App\Filament\Admin\Resources\Library\BookSuppliers\Pages\ViewBookSupplier;
use App\Filament\Admin\Resources\Library\BookSuppliers\Pages\EditBookSupplier;
use App\Filament\Admin\Resources\Library\BookSupplierResource\Pages;
use App\Filament\Admin\Resources\Library\BookSupplierResource\RelationManagers;
use App\Models\Library\BookSupplier;
use App\Models\LibraryBookSupplier;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookSupplierResource extends Resource
{
    protected static ?string $model = LibraryBookSupplier::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Library Management System';

    protected static ?string $navigationLabel = 'Supplier';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                TextInput::make('address')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('primary_contact_number')
                    ->numeric()
                    ->maxLength(15)
                    ->default(null),
                TextInput::make('secondary_contact_number')
                    ->numeric()
                    ->maxLength(15)
                    ->default(null),
                TextInput::make('email')
                    ->email()
                    ->maxLength(100)
                    ->default(null),
                Toggle::make('is_active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('address')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('primary_contact_number')
                    ->label('Primary No.')
                    ->searchable(),
                TextColumn::make('secondary_contact_number')
                    ->label('Secondary No.')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('createdBy')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updatedBy')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deletedBy')
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
            ->defaultSort('id', 'desc')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookSuppliers::route('/'),
            'create' => CreateBookSupplier::route('/create'),
            'view' => ViewBookSupplier::route('/{record}'),
            'edit' => EditBookSupplier::route('/{record}/edit'),
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
        return LibraryBookSupplier::where('is_active', 1)
            ->count();
    }
}
