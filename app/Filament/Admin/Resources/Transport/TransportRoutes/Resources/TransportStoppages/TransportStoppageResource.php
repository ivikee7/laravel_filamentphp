<?php

namespace App\Filament\Admin\Resources\Transport\TransportRoutes\Resources\TransportStoppages;

use App\Filament\Admin\Resources\Transport\TransportRoutes\Resources\TransportStoppages\Pages\CreateTransportStoppage;
use App\Filament\Admin\Resources\Transport\TransportRoutes\Resources\TransportStoppages\Pages\EditTransportStoppage;
use App\Filament\Admin\Resources\Transport\TransportRoutes\Resources\TransportStoppages\Pages\ViewTransportStoppage;
use App\Filament\Admin\Resources\Transport\TransportRoutes\Resources\TransportStoppages\Schemas\TransportStoppageForm;
use App\Filament\Admin\Resources\Transport\TransportRoutes\Resources\TransportStoppages\Schemas\TransportStoppageInfolist;
use App\Filament\Admin\Resources\Transport\TransportRoutes\Resources\TransportStoppages\Tables\TransportStoppagesTable;
use App\Filament\Admin\Resources\Transport\TransportRoutes\TransportRouteResource;
use App\Models\TransportStoppage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransportStoppageResource extends Resource
{
    protected static ?string $model = TransportStoppage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = TransportRouteResource::class;

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
            'create' => CreateTransportStoppage::route('/create'),
            'view' => ViewTransportStoppage::route('/{record}'),
            'edit' => EditTransportStoppage::route('/{record}/edit'),
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
