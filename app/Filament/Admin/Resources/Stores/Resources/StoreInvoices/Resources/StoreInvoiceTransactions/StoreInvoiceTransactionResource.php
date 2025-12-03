<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Pages\CreateStoreInvoiceTransaction;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Pages\EditStoreInvoiceTransaction;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Pages\ViewStoreInvoiceTransaction;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Schemas\StoreInvoiceTransactionForm;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Schemas\StoreInvoiceTransactionInfolist;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Tables\StoreInvoiceTransactionsTable;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\StoreInvoiceResource;
use App\Models\StoreInvoiceTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreInvoiceTransactionResource extends Resource
{
    protected static ?string $model = StoreInvoiceTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = StoreInvoiceResource::class;

    protected static ?string $modelLabel = 'Transaction';

    public static function form(Schema $schema): Schema
    {
        return StoreInvoiceTransactionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoreInvoiceTransactionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoreInvoiceTransactionsTable::configure($table);
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
//            'create' => CreateStoreInvoiceTransaction::route('/create'),
//            'view' => ViewStoreInvoiceTransaction::route('/{record}'),
//            'edit' => EditStoreInvoiceTransaction::route('/{record}/edit'),
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
