<?php

namespace App\Filament\Admin\Resources\Transport\TransportRoutes\RelationManagers;

use App\Filament\Admin\Resources\Transport\TransportRoutes\Resources\TransportStoppages\TransportStoppageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StoppageRelationManager extends RelationManager
{
    protected static string $relationship = 'transportStoppages';

    protected static ?string $relatedResource = TransportStoppageResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
