<?php

namespace App\Filament\Admin\Resources\Library\BookPublishers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\Library\BookPublishers\Pages\ListBookPublishers;
use App\Filament\Admin\Resources\Library\BookPublishers\Pages\CreateBookPublisher;
use App\Filament\Admin\Resources\Library\BookPublishers\Pages\ViewBookPublisher;
use App\Filament\Admin\Resources\Library\BookPublishers\Pages\EditBookPublisher;
use App\Filament\Admin\Resources\Library\BookPublisherResource\Pages;
use App\Filament\Admin\Resources\Library\BookPublisherResource\RelationManagers;
use App\Models\Library\BookPublisher;
use App\Models\LibraryBookPublisher;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookPublisherResource extends Resource
{
    protected static ?string $model = LibraryBookPublisher::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Library Management System';

    protected static ?string $navigationLabel = 'Publisher';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                TextInput::make('email')
                    ->email()
                    ->maxLength(50)
                    ->default(null),
                TextInput::make('primary_contact_number')
                    ->numeric()
                    ->maxLength(15)
                    ->default(null),
                TextInput::make('secondary_contact_number')
                    ->numeric()
                    ->maxLength(15)
                    ->default(null),
                TextInput::make('location')
                    ->maxLength(100)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('primary_contact_number')
                    ->label('Primary No.')
                    ->numeric()
                    ->searchable(),
                TextColumn::make('secondary_contact_number')
                    ->label('Secondary No.')
                    ->numeric()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('createdBy.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updatedBy.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deletedBy.name')
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
            'index' => ListBookPublishers::route('/'),
            'create' => CreateBookPublisher::route('/create'),
            'view' => ViewBookPublisher::route('/{record}'),
            'edit' => EditBookPublisher::route('/{record}/edit'),
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
        return LibraryBookPublisher::count();
    }
}
