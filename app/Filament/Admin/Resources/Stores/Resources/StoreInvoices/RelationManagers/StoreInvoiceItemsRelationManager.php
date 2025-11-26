<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\RelationManagers;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceItems\StoreInvoiceItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StoreInvoiceItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'storeInvoiceItems';

    protected static ?string $relatedResource = StoreInvoiceItemResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
