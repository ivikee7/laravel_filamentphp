<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceItems;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceItems\Pages\CreateStoreInvoiceItem;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceItems\Pages\EditStoreInvoiceItem;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceItems\Pages\ViewStoreInvoiceItem;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceItems\Schemas\StoreInvoiceItemForm;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceItems\Schemas\StoreInvoiceItemInfolist;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceItems\Tables\StoreInvoiceItemsTable;
use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\StoreInvoiceResource;
use App\Models\StoreInvoiceItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreInvoiceItemResource extends Resource
{
    protected static ?string $model = StoreInvoiceItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = StoreInvoiceResource::class;

    protected static ?string $modelLabel = 'Items';

    public static function form(Schema $schema): Schema
    {
        return StoreInvoiceItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoreInvoiceItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoreInvoiceItemsTable::configure($table);
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
            'create' => CreateStoreInvoiceItem::route('/create'),
            'view' => ViewStoreInvoiceItem::route('/{record}'),
            'edit' => EditStoreInvoiceItem::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
