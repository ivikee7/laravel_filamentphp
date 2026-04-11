<?php

namespace App\Filament\Admin\Resources\Transport\TransportBuses\Resources\TransportFuelLogs;

use App\Filament\Admin\Resources\Transport\TransportBuses\Resources\TransportFuelLogs\Pages\CreateTransportFuelLog;
use App\Filament\Admin\Resources\Transport\TransportBuses\Resources\TransportFuelLogs\Pages\EditTransportFuelLog;
use App\Filament\Admin\Resources\Transport\TransportBuses\Resources\TransportFuelLogs\Pages\ViewTransportFuelLog;
use App\Filament\Admin\Resources\Transport\TransportBuses\Resources\TransportFuelLogs\Schemas\TransportFuelLogForm;
use App\Filament\Admin\Resources\Transport\TransportBuses\Resources\TransportFuelLogs\Schemas\TransportFuelLogInfolist;
use App\Filament\Admin\Resources\Transport\TransportBuses\Resources\TransportFuelLogs\Tables\TransportFuelLogsTable;
use App\Filament\Admin\Resources\Transport\TransportBuses\TransportBusResource;
use App\Models\TransportFuelLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransportFuelLogResource extends Resource
{
    protected static ?string $model = TransportFuelLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = TransportBusResource::class;

    protected static ?string $recordTitleAttribute = 'it';

    public static function form(Schema $schema): Schema
    {
        return TransportFuelLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransportFuelLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransportFuelLogsTable::configure($table);
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
            'create' => CreateTransportFuelLog::route('/create'),
            'view' => ViewTransportFuelLog::route('/{record}'),
            'edit' => EditTransportFuelLog::route('/{record}/edit'),
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
