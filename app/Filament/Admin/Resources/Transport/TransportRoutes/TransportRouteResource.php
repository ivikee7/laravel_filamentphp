<?php

namespace App\Filament\Admin\Resources\Transport\TransportRoutes;

use App\Filament\Admin\Resources\Transport\TransportRoutes\Pages\CreateTransportRoute;
use App\Filament\Admin\Resources\Transport\TransportRoutes\Pages\EditTransportRoute;
use App\Filament\Admin\Resources\Transport\TransportRoutes\Pages\ListTransportRoutes;
use App\Filament\Admin\Resources\Transport\TransportRoutes\Pages\ViewTransportRoute;
use App\Filament\Admin\Resources\Transport\TransportRoutes\RelationManagers\StoppageRelationManager;
use App\Filament\Admin\Resources\Transport\TransportRoutes\Schemas\TransportRouteForm;
use App\Filament\Admin\Resources\Transport\TransportRoutes\Schemas\TransportRouteInfolist;
use App\Filament\Admin\Resources\Transport\TransportRoutes\Tables\TransportRoutesTable;
use App\Models\TransportRoute;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TransportRouteResource extends Resource
{
    protected static ?string $model = TransportRoute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static string | UnitEnum | null $navigationGroup = 'Transport';

    public static function form(Schema $schema): Schema
    {
        return TransportRouteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransportRouteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransportRoutesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'Stoppages' => StoppageRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransportRoutes::route('/'),
            'create' => CreateTransportRoute::route('/create'),
            'view' => ViewTransportRoute::route('/{record}'),
            'edit' => EditTransportRoute::route('/{record}/edit'),
        ];
    }
}
