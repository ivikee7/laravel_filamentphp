<?php

namespace App\Filament\Admin\Resources\Transport\TransportBuses;

use App\Filament\Admin\Resources\Transport\TransportBuses\Pages\CreateTransportBus;
use App\Filament\Admin\Resources\Transport\TransportBuses\Pages\EditTransportBus;
use App\Filament\Admin\Resources\Transport\TransportBuses\Pages\ListTransportBuses;
use App\Filament\Admin\Resources\Transport\TransportBuses\Pages\ViewTransportBus;
use App\Filament\Admin\Resources\Transport\TransportBuses\RelationManagers\TransportFuelLogsRelationManager;
use App\Filament\Admin\Resources\Transport\TransportBuses\Schemas\TransportBusForm;
use App\Filament\Admin\Resources\Transport\TransportBuses\Schemas\TransportBusInfolist;
use App\Filament\Admin\Resources\Transport\TransportBuses\Tables\TransportBusesTable;
use App\Models\TransportBus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TransportBusResource extends Resource
{
    protected static ?string $model = TransportBus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


    protected static string | UnitEnum | null $navigationGroup = 'Transport';

    public static function form(Schema $schema): Schema
    {
        return TransportBusForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransportBusInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransportBusesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'TransportFuelLogs' => TransportFuelLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransportBuses::route('/'),
            'create' => CreateTransportBus::route('/create'),
            'view' => ViewTransportBus::route('/{record}'),
            'edit' => EditTransportBus::route('/{record}/edit'),
        ];
    }
}
