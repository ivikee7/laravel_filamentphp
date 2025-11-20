<?php

namespace App\Filament\Admin\Resources\Stores\RelationManagers;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\StoreInvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StoreInvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'storeInvoices';

    protected static ?string $relatedResource = StoreInvoiceResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
