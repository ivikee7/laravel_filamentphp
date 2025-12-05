<?php

namespace App\Filament\Admin\Resources\Stores;

use App\Filament\Admin\Resources\Stores\Pages\ListInvoices;
use App\Filament\Admin\Resources\Stores\Pages\ListProducts;
use App\Filament\Admin\Resources\Stores\Pages\ListStudents;
use App\Filament\Admin\Resources\Stores\Pages\ListTransactions;
use App\Filament\Admin\Resources\Stores\Pages\PrintInvoice;
use App\Filament\Admin\Resources\Stores\Pages\StudentCart;
use App\Filament\Admin\Resources\Stores\Pages\StudentProducts;
use App\Filament\Admin\Resources\Stores\Pages\CreateStore;
use App\Filament\Admin\Resources\Stores\Pages\EditStore;
use App\Filament\Admin\Resources\Stores\Pages\ListStores;
use App\Filament\Admin\Resources\Stores\Pages\ViewInvoice;
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
use SebastianBergmann\LinesOfCode\Counter;
use UnitEnum;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Store management system';

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
            //
            'list-products' => ListProducts::route('/{record}/list-products'),
            'list-students' => ListStudents::route('/{record}/list-students'),
            'students-products' => StudentProducts::route('/{record}/list-students/{student}/products'),
            'students-cart' => StudentCart::route('/{record}/list-students/{student}/cart'),
            'print-invoice' => PrintInvoice::route('/{record}/store-invoices/{invoiceId}/print'),
            //
            'list-invoices' => ListInvoices::route('/{record}/list-invoices'),
            'view-invoice' => ViewInvoice::route('/{record}/list-invoices/{invoiceId}'),
            //
            'list-transactions' => ListTransactions::route('/{record}/list-transactions'),
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
