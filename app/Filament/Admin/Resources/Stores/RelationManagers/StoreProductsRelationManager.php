<?php

namespace App\Filament\Admin\Resources\Stores\RelationManagers;

use App\Filament\Admin\Resources\Stores\Resources\StoreProducts\StoreProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StoreProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'StoreProducts';

    protected static ?string $relatedResource = StoreProductResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
