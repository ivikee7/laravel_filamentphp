<?php

namespace App\Filament\Admin\Resources\Transport\TransportStoppages;

use App\Filament\Admin\Resources\Transport\TransportStoppages\Pages\CreateTransportStoppage;
use App\Filament\Admin\Resources\Transport\TransportStoppages\Pages\EditTransportStoppage;
use App\Filament\Admin\Resources\Transport\TransportStoppages\Pages\ListTransportStoppages;
use App\Filament\Admin\Resources\Transport\TransportStoppages\Pages\ViewTransportStoppage;
use App\Filament\Admin\Resources\Transport\TransportStoppages\Schemas\TransportStoppageForm;
use App\Filament\Admin\Resources\Transport\TransportStoppages\Schemas\TransportStoppageInfolist;
use App\Filament\Admin\Resources\Transport\TransportStoppages\Tables\TransportStoppagesTable;
use App\Models\TransportStoppage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TransportStoppageResource extends Resource
{
    protected static ?string $model = TransportStoppage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


    protected static string | UnitEnum | null $navigationGroup = 'Transport';

    public static function form(Schema $schema): Schema
    {
        return TransportStoppageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransportStoppageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransportStoppagesTable::configure($table);
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
            'index' => ListTransportStoppages::route('/'),
            'create' => CreateTransportStoppage::route('/create'),
            'view' => ViewTransportStoppage::route('/{record}'),
            'edit' => EditTransportStoppage::route('/{record}/edit'),
        ];
    }
}
