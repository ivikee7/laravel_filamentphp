<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem;

use App\Filament\Admin\Resources\StoreManagementSystem\ProductResource\Pages;
use App\Filament\Admin\Resources\StoreManagementSystem\ProductResource\RelationManagers;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Store Management System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('store_id')
                    ->relationship('store', 'name'),
                Forms\Components\Select::make('academic_year_id')
                    ->relationship('academicYear', 'name'),
                Forms\Components\Select::make('class_id')
                    ->relationship('class', 'name'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
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
                Tables\Columns\TextColumn::make('academicYear.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('class.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('store.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deletedBy.name')
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
            ])
            ->actions([
                Tables\Actions\Action::make('add_to_cart')
                    ->label('Add to Cart')
                    ->icon('heroicon-o-plus')
                    ->action(function ($record, array $data) {
                        $studentId = request()->query('student_id');
                        if ($studentId) {
                            Cart::updateOrCreate([
                                'student_id' => $studentId,
                                'product_id' => $record->id,
                            ], [
                                'quantity' => \DB::raw('quantity + 1'),
                            ]);
                        }
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
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
