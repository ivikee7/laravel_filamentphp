<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Pages\CreateStoreInvoice;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Pages\EditStoreInvoice;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Pages\ViewStoreInvoice;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\RelationManagers\StoreTransactionsRelationManager;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Schemas\StoreInvoiceForm;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Schemas\StoreInvoiceInfolist;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Tables\StoreInvoicesTable;
use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Models\StoreInvoice;
use App\Models\StoreTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreInvoiceResource extends Resource
{
    protected static ?string $model = StoreInvoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = StoreResource::class;

    public static function form(Schema $schema): Schema
    {
        return StoreInvoiceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoreInvoiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoreInvoicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            StoreTransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateStoreInvoice::route('/create'),
            'view' => ViewStoreInvoice::route('/{record}'),
            'edit' => EditStoreInvoice::route('/{record}/edit'),
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
