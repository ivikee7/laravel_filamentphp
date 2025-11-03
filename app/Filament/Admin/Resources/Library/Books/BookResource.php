<?php

namespace App\Filament\Admin\Resources\Library\Books;

use Faker\Core\Number;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\Library\Books\Pages\ListBooks;
use App\Filament\Admin\Resources\Library\Books\Pages\CreateBook;
use App\Filament\Admin\Resources\Library\Books\Pages\ViewBook;
use App\Filament\Admin\Resources\Library\Books\Pages\EditBook;
use App\Filament\Admin\Resources\Library\BookResource\Pages;
use App\Filament\Admin\Resources\Library\BookResource\RelationManagers;
use App\Models\Library\Book;
use App\Models\LibraryBook;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BookResource extends Resource
{
    protected static ?string $model = LibraryBook::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Library Management System';

    protected static ?string $navigationLabel = 'Book';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(50)
                    ->live()
                    ->datalist(function (?string $state): array {
                        return LibraryBook::query()
                            ->where('title', 'like', "%{$state}%")
                            ->pluck('title')
                            ->unique()
                            ->toArray();
                    })
                    ->autocomplete(false),
                TextInput::make('edition')
                    ->maxLength(50)
                    ->default(null),
                TextInput::make('price')
                    ->numeric()
                    ->default(null)
                    ->prefix('₹'),
                TextInput::make('pages')
                    ->numeric()
                    ->default(null),
                TextInput::make('isbn_number')
                    ->maxLength(100)
                    ->default(null),
                DatePicker::make('purchased_at'),
                DatePicker::make('published_at')
                    ->label('Published Year')
                    ->displayFormat('Y')
                    ->format('Y')
                    ->native(false)
                    ->required(),
                TextInput::make('notes')
                    ->maxLength(255)
                    ->default(null),
                Select::make('authors')
                    ->label("Author's")
                    ->relationship('authors', 'name')
                    ->default(null)
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->unique(),
                        TextInput::make('notes')
                            ->required()
                            ->maxLength(100)
                            ->unique(),
                    ]),
                TextInput::make('author')
                    ->maxLength(100)
                    ->default(null)
                    ->live()
                    ->datalist(function (?string $state): array {
                        return LibraryBook::query()
                            ->where('author', 'like', "%{$state}%")
                            ->pluck('author')
                            ->unique()
                            ->toArray();
                    })
                    ->autocomplete(false),
                Select::make('publisher_id')
                    ->relationship('publisher', 'name')
                    ->default(null),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->default(null),
                Select::make('location_id')
                    ->relationship('location', 'name')
                    ->default(null),
                Select::make('language_id')
                    ->relationship('language', 'name')
                    ->default(null),
                Select::make('class_id')
                    ->relationship('class', 'name')
                    ->default(null),
                Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->default(null),
                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->default(null),
                TextInput::make('accession_number')
                    ->maxLength(50)
                    ->disabledOn(['edit']),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->wrap()
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('edition')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')
                    ->money('INR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pages')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('isbn_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('purchased_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('published_at')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('notes')
                    ->wrap()
                    ->searchable(),
//                TextColumn::make('author.name')
//                    ->wrap()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('author_name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('publisher.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location.name')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('language.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('class.name')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('subject.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('supplier.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('accession_number')
                    ->numeric()
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('createdBy.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updatedBy.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deletedBy.name')
                    ->wrap()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                TrashedFilter::make(),
                Filter::make('isbn_number_filter') // It's good practice to give filters a descriptive name
                ->schema([
                    TextInput::make('isbn_number')
                        ->label('ISBN Number') // Add a user-friendly label
                        ->maxLength(100)
                        ->default(null), // Defaulting to null is usually not necessary unless you have a specific reason
                ])
                    ->query(function (Builder $query, array $data): Builder {
                        // Corrected: Filter by 'isbn_number' column, not 'name'
                        // Corrected: The second argument of `when` closure is the value itself,
                        // so we rename `$data` to `$isbnNumber` for clarity within this inner closure.
                        return $query
                            ->when(
                                $data['isbn_number'], // This refers to the value from the form field
                                fn(Builder $query, $isbnNumber): Builder => $query->where('isbn_number', 'like', '%' . $isbnNumber . '%'),
                            );
                    }),
            ])
            ->columnManagerColumns(4)
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
            'index' => ListBooks::route('/'),
            'create' => CreateBook::route('/create'),
            'view' => ViewBook::route('/{record}'),
            'edit' => EditBook::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereDoesntHave('borrows', function ($query) {
                $query->whereNotNull('received_at');
                $query->orWhereNotNull('received_by');
            });
    }

    public static function getNavigationBadge(): ?string
    {
        return LibraryBook::count();
    }

}
