<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Products;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use DB;
use Filament\Actions\BulkActionGroup;
use App\Filament\Admin\Resources\StoreManagementSystem\Products\Pages\ListProducts;
use App\Filament\Admin\Resources\StoreManagementSystem\Products\Pages\CreateProduct;
use App\Filament\Admin\Resources\StoreManagementSystem\Products\Pages\ViewProduct;
use App\Filament\Admin\Resources\StoreManagementSystem\Products\Pages\EditProduct;
use App\Filament\Admin\Resources\StoreManagementSystem\ProductResource\Pages;
use App\Filament\Admin\Resources\StoreManagementSystem\ProductResource\RelationManagers;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Store Management System';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('store_id')
                    ->relationship('store', 'name'),
                Select::make('academic_year_id')
                    ->relationship('academicYear', 'name'),
                Select::make('class_id')
                    ->relationship('class', 'name'),
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('₹'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
//            ->modifyQueryUsing(function (Builder $query) {
//                if ($studentId = request()->query('student_id')) {
//                    $student = User::find($studentId)?->student;
//
//                    if ($student) {
//                        $classId = $student->currentClassAssignment?->class_id;
//                        $yearId = $student->currentClassAssignment?->academic_year_id;
//
//                        $query->whereHas('class', fn ($q) => $q->where('id', $classId))
//                            ->where('academic_year_id', $yearId);
//                    }
//                }
//            })
            ->columns([
                TextColumn::make('academicYear.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('class.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('price')
                    ->money('INR')
                    ->sortable(),
                TextColumn::make('store.name')
                    ->numeric()
                    ->sortable(),
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
            ->filters([
            ])
            ->recordActions([
                Action::make('add_to_cart')
                    ->label('Add to Cart')
                    ->icon('heroicon-o-plus')
                    ->action(function ($record, array $data) {
                        $studentId = request()->query('student_id');
                        if ($studentId) {
                            Cart::updateOrCreate([
                                'student_id' => $studentId,
                                'product_id' => $record->id,
                            ], [
                                'quantity' => DB::raw('quantity + 1'),
                            ]);
                        }
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'view' => ViewProduct::route('/{record}'),
            'edit' => EditProduct::route('/{record}/edit'),
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
        return Product::count();
    }
}
