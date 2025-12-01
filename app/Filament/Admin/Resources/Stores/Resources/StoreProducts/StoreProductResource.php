<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreProducts;

use App\Filament\Admin\Resources\Stores\Resources\StoreProducts\Pages\CreateStoreProduct;
use App\Filament\Admin\Resources\Stores\Resources\StoreProducts\Pages\EditStoreProduct;
use App\Filament\Admin\Resources\Stores\Resources\StoreProducts\Pages\ViewStoreProduct;
use App\Filament\Admin\Resources\Stores\Resources\StoreProducts\Schemas\StoreProductForm;
use App\Filament\Admin\Resources\Stores\Resources\StoreProducts\Schemas\StoreProductInfolist;
use App\Filament\Admin\Resources\Stores\Resources\StoreProducts\Tables\StoreProductsTable;
use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\StoreProduct;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreProductResource extends Resource
{
    protected static ?string $model = StoreProduct::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = StoreResource::class;

    protected static ?string $modelLabel = "Product";

    public static function form(Schema $schema): Schema
    {
        return StoreProductForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoreProductInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoreProductsTable::configure($table);
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
            'create' => CreateStoreProduct::route('/create'),
            'view' => ViewStoreProduct::route('/{record}'),
            'edit' => EditStoreProduct::route('/{record}/edit'),
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
