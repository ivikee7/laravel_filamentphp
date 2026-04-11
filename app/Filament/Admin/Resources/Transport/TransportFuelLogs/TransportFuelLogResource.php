<?php

namespace App\Filament\Admin\Resources\Transport\TransportFuelLogs;

use App\Filament\Admin\Resources\Transport\TransportFuelLogs\Pages\CreateTransportFuelLog;
use App\Filament\Admin\Resources\Transport\TransportFuelLogs\Pages\EditTransportFuelLog;
use App\Filament\Admin\Resources\Transport\TransportFuelLogs\Pages\ListTransportFuelLogs;
use App\Filament\Admin\Resources\Transport\TransportFuelLogs\Pages\ViewTransportFuelLog;
use App\Filament\Admin\Resources\Transport\TransportFuelLogs\Schemas\TransportFuelLogForm;
use App\Filament\Admin\Resources\Transport\TransportFuelLogs\Schemas\TransportFuelLogInfolist;
use App\Filament\Admin\Resources\Transport\TransportFuelLogs\Tables\TransportFuelLogsTable;
use App\Models\TransportFuelLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TransportFuelLogResource extends Resource
{
    protected static ?string $model = TransportFuelLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static string | UnitEnum | null $navigationGroup = 'Transport';

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
            'index' => ListTransportFuelLogs::route('/'),
            'create' => CreateTransportFuelLog::route('/create'),
            'view' => ViewTransportFuelLog::route('/{record}'),
            'edit' => EditTransportFuelLog::route('/{record}/edit'),
        ];
    }
}
