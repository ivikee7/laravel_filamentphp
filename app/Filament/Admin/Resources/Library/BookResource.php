<?php

namespace App\Filament\Admin\Resources\Library;

use App\Filament\Admin\Resources\Library\BookResource\Pages;
use App\Filament\Admin\Resources\Library\BookResource\RelationManagers;
use App\Models\Library\Book;
use App\Models\LibraryBook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BookResource extends Resource
{
    protected static ?string $model = LibraryBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Library Management System';

    protected static ?string $navigationLabel = 'Book';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('edition')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->default(null)
                    ->prefix('₹'),
                Forms\Components\TextInput::make('pages')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('isbn_number')
                    ->maxLength(100)
                    ->default(null),
                Forms\Components\DatePicker::make('purchased_at'),
                Forms\Components\DatePicker::make('published_at')
                    ->label('Published Year')
                    ->displayFormat('Y')
                    ->format('Y')
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('notes')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('author_id')
                    ->relationship('author', 'name')
                    ->default(null),
                Forms\Components\Select::make('publisher_id')
                    ->relationship('publisher', 'name')
                    ->default(null),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->default(null),
                Forms\Components\Select::make('location_id')
                    ->relationship('location', 'name')
                    ->default(null),
                Forms\Components\Select::make('language_id')
                    ->relationship('language', 'name')
                    ->default(null),
                Forms\Components\Select::make('class_id')
                    ->relationship('class', 'name')
                    ->default(null),
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->default(null),
                Forms\Components\Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->default(null),
                Forms\Components\TextInput::make('accession_number')
                    ->maxLength(50)
                    ->default(null),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('edition')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('price')
                    ->money('INR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pages')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('isbn_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('purchased_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('published_at')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('notes')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('publisher.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('category.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('location.name')
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('language.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('class.name')
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('accession_number')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deletedBy.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'view' => Pages\ViewBook::route('/{record}'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereDoesntHave('borrows', function ($query) {
                $query->whereNull('received_at');
                $query->whereNull('received_by');
            });
    }
}
