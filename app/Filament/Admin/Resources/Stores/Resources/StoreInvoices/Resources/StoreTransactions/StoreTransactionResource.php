<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions\Pages\CreateStoreTransaction;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions\Pages\EditStoreTransaction;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions\Pages\ViewStoreTransaction;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions\Schemas\StoreTransactionForm;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions\Schemas\StoreTransactionInfolist;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions\Tables\StoreTransactionsTable;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\StoreInvoiceResource;
use App\Models\StoreTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreTransactionResource extends Resource
{
    protected static ?string $model = StoreTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = StoreInvoiceResource::class;

    public static function form(Schema $schema): Schema
    {
        return StoreTransactionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoreTransactionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoreTransactionsTable::configure($table);
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
            'create' => CreateStoreTransaction::route('/create'),
            'view' => ViewStoreTransaction::route('/{record}'),
            'edit' => EditStoreTransaction::route('/{record}/edit'),
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
