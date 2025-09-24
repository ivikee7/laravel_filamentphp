<?php

namespace App\Filament\Admin\Resources\Library\BookBorrows;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\Library\BookBorrows\Pages\ListBookBorrows;
use App\Filament\Admin\Resources\Library\BookBorrows\Pages\CreateBookBorrow;
use App\Filament\Admin\Resources\Library\BookBorrows\Pages\ViewBookBorrow;
use App\Filament\Admin\Resources\Library\BookBorrows\Pages\EditBookBorrow;
use App\Filament\Admin\Resources\Library\BookBorrowResource\Pages;
use App\Filament\Admin\Resources\Library\BookBorrowResource\RelationManagers;
use App\Models\Library\BookBorrow;
use App\Models\LibraryBookBorrow;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BookBorrowResource extends Resource
{
    protected static ?string $model = LibraryBookBorrow::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Library Management System';

    protected static ?string $navigationLabel = 'Borrow';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('book_id')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('due_date')
                    ->required(),
                TextInput::make('notes')
                    ->maxLength(100)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('book.title')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('notes')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('createdBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updatedBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deletedBy.name')
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
            ->defaultSort('id', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('received')
                    ->label('Received')
                    ->action(function ($record) {
                        $record->update([
                            'received_by' => Auth::id(),
                            'received_at' => now(),
                        ]);
                    })
                    ->visible(fn($record) => $record && is_null($record->received_at))
                    ->requiresConfirmation()
                    ->color('success')
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
            'index' => ListBookBorrows::route('/'),
            'create' => CreateBookBorrow::route('/create'),
            'view' => ViewBookBorrow::route('/{record}'),
            'edit' => EditBookBorrow::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereNull('received_at')
            ->whereNull('received_by');
    }

    public static function getNavigationBadge(): ?string
    {
        return LibraryBookBorrow::count();
    }
}
