<?php

namespace App\Filament\Admin\Resources\Stores;

use App\Filament\Admin\Resources\Stores\Pages\Seller;
use App\Filament\Admin\Resources\Stores\Pages\StoreInvoices;
use App\Filament\Admin\Resources\Stores\Pages\StoreTransactions;
use App\Filament\Admin\Resources\Stores\Pages\StudentCart;
use App\Filament\Admin\Resources\Stores\Pages\StudentProducts;
use App\Filament\Admin\Resources\Stores\Pages\CreateStore;
use App\Filament\Admin\Resources\Stores\Pages\EditStore;
use App\Filament\Admin\Resources\Stores\Pages\ListStores;
use App\Filament\Admin\Resources\Stores\Pages\ViewStore;
use App\Filament\Admin\Resources\Stores\RelationManagers\StoreInvoicesRelationManager;
use App\Filament\Admin\Resources\Stores\RelationManagers\StoreProductsRelationManager;
use App\Filament\Admin\Resources\Stores\Schemas\StoreForm;
use App\Filament\Admin\Resources\Stores\Schemas\StoreInfolist;
use App\Filament\Admin\Resources\Stores\Tables\StoresTable;
use App\Models\Store;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StoreForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoreInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            StoreInvoicesRelationManager::class,
            StoreProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStores::route('/'),
            'create' => CreateStore::route('/create'),
            'view' => ViewStore::route('/{record}'),
            'edit' => EditStore::route('/{record}/edit'),
            // custom
            'seller' => Seller::route('/{record}/seller'),
            'students-products' => StudentProducts::route('/{record}/seller/{student}/products'),
            'students-cart' => StudentCart::route('/{record}/seller-student/{student}/cart'),
//            'invoices' => StoreInvoices::route('/{record}/invoices'),
//            'transactions' => StoreTransactions::route('/{record}/transactions'),
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
