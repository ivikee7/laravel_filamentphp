<?php

namespace App\Filament\Admin\Resources\Students\Resources\Products;

use App\Filament\Admin\Resources\Students\Resources\Products\Pages\CreateProduct;
use App\Filament\Admin\Resources\Students\Resources\Products\Pages\EditProduct;
use App\Filament\Admin\Resources\Students\Resources\Products\Pages\ViewProduct;
use App\Filament\Admin\Resources\Students\Resources\Products\Schemas\ProductForm;
use App\Filament\Admin\Resources\Students\Resources\Products\Schemas\ProductInfolist;
use App\Filament\Admin\Resources\Students\Resources\Products\Tables\ProductsTable;
use App\Filament\Admin\Resources\Students\StudentResource;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = StudentResource::class;

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
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
            'create' => CreateProduct::route('/create'),
            'view' => ViewProduct::route('/{record}'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
